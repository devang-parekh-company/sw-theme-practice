<?php
declare(strict_types=1);

namespace SchoolWiseCommerceControls\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\Address\Listing\AddressListingPageLoadedEvent;
use Shopware\Storefront\Page\Checkout\Confirm\CheckoutConfirmPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Checkout\Cart\Error\ErrorCollection;
use Shopware\Core\Checkout\Cart\Address\Error\ShippingAddressBlockedError;

class ShippingAddressSubscriber implements EventSubscriberInterface
{
    private EntityRepository $customerGroupRepository;
    private EntityRepository $addressRepository;
    private EntityRepository $salutationRepository;
    private EntityRepository $customerRepository;
    private EntityRepository $countryRepository;
    private EntityRepository $salesChannelCountryRepository;


    public function __construct(
        EntityRepository $customerGroupRepository,
        EntityRepository $addressRepository,
        EntityRepository $salutationRepository,
        EntityRepository $customerRepository,
        EntityRepository $countryRepository,
        EntityRepository $salesChannelCountryRepository,
    )
    {
        $this->customerGroupRepository = $customerGroupRepository;
        $this->addressRepository = $addressRepository;
        $this->salutationRepository = $salutationRepository;
        $this->customerRepository = $customerRepository;
        $this->countryRepository = $countryRepository;
        $this->salesChannelCountryRepository = $salesChannelCountryRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CheckoutConfirmPageLoadedEvent::class => 'onCheckoutConfirmPageLoaded',
            AddressListingPageLoadedEvent::class => 'onCheckoutConfirmPageLoaded',
        ];
    }

    public function onCheckoutConfirmPageLoaded($event): void
    {
        $context = $event->getSalesChannelContext();
        $customer = $context->getCustomer();

        if (!$customer) {
            return;
        }

        $customerGroupId = $customer->getGroupId();
        $criteria = new Criteria([$customerGroupId]);
        $customerGroup = $this->customerGroupRepository->search($criteria, $context->getContext())->first();

        $customFields = $customerGroup->getTranslated()['customFields'];
        $shippingEnabled = $customFields['shipping_enabled_for_home'] ?? false;

        $requiredFields = [
            'school_city',
            'school_address',
            'school_country',
            'school_last_name',
            'school_first_name',
            'school_postal_code',
        ];
        $hasAllRequiredFields = true;

        foreach ($requiredFields as $field) {
            if (empty($customFields[$field])) {
                $hasAllRequiredFields = false;
                break;
            }
        }

        //Add the Custom Fields Vaules
        $event->getPage()->addArrayExtension("shippingEnabled", [$shippingEnabled]);

        if (!$shippingEnabled && $hasAllRequiredFields) {
            $this->updateShippingAddress($customer, $customFields, $context);
        }
    }

    private function updateShippingAddress($customer, $customFields, $context): void
    {
        $defaultSalutationId = $this->getDefaultSalutationId($context, 'not_specified');

        $countryId = $customFields['school_country'];
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $countryId));
        $country = $this->countryRepository->search($criteria, $context->getContext())->first();

        if ($this->isSalesChannelCountry($countryId, $context)) {
            $context->getCustomer()->getActiveShippingAddress()->setCountry($country);
        } else {
            $error = new ErrorCollection();
            $error->add(new ShippingAddressBlockedError((string)$country->getTranslation('name')));
            return;
        }

        $schoolAddressData = [
            'id' => $customer->getActiveShippingAddress()->getId(),
            'customerId' => $customer->getId(),
            'salutationId' => $defaultSalutationId,
            'firstName' => $customFields['school_first_name'],
            'lastName' => $customFields['school_last_name'],
            'street' => $customFields['school_address'] ?? null,
            'zipcode' => $customFields['school_postal_code'] ?? null,
            'city' => $customFields['school_city'] ?? null,
            'countryId' => $countryId ?? null,
            'company' => null,
            'department' => null,
            'additionalAddressLine1' => null,
            'additionalAddressLine2' => null,
        ];

        // Update the shipping address
        $this->addressRepository->upsert([$schoolAddressData], $context->getContext());

        $context->getCustomer()->getActiveShippingAddress()->setFirstName($schoolAddressData['firstName']);
        $context->getCustomer()->getActiveShippingAddress()->setLastName($schoolAddressData['lastName']);
        $context->getCustomer()->getActiveShippingAddress()->setStreet($schoolAddressData['street']);
        $context->getCustomer()->getActiveShippingAddress()->setZipCode($schoolAddressData['zipcode']);
        $context->getCustomer()->getActiveShippingAddress()->setCity($schoolAddressData['city']);
        $context->getCustomer()->getActiveShippingAddress()->setCountryId($countryId);
        $context->getCustomer()->getActiveShippingAddress()->setSalutationId($defaultSalutationId);
        $context->getCustomer()->getActiveShippingAddress()->setCompany('');
        $context->getCustomer()->getActiveShippingAddress()->setDepartment('');
        $context->getCustomer()->getActiveShippingAddress()->setAdditionalAddressLine1('');
        $context->getCustomer()->getActiveShippingAddress()->setAdditionalAddressLine2('');
    }

    private function getDefaultSalutationId($context, $key): ?string
    {
        // Fetch salutation ID where salutation key matches "not_specified"
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('salutationKey', $key));

        $salutation = $this->salutationRepository->search($criteria, $context->getContext())->first();

        return $salutation ? $salutation->getId() : null;
    }

    private function isSalesChannelCountry(string $countryId, SalesChannelContext $context): bool
    {

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('salesChannelId', $context->getSalesChannelId()));
        $criteria->addFilter(new EqualsFilter('countryId', $countryId));

        return $this->salesChannelCountryRepository->searchIds($criteria, $context->getContext())->getTotal() !== 0;
    }
}
