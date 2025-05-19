<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Subscriber;

use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Storefront\Event\StorefrontRenderEvent;
use Shopware\Core\Checkout\Customer\Event\CustomerRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class RegisterFormSubscriber implements EventSubscriberInterface
{
    private EntityRepository $customerGroupRepository;
    private EntityRepository $customerRepository;

    private CartService $cartService;

    protected $requestStack;

    public function __construct(
        EntityRepository $customerGroupRepository,
        EntityRepository $customerRepository,
        RequestStack $requestStack,
        CartService $cartService,
    ) {
        $this->customerGroupRepository = $customerGroupRepository;
        $this->customerRepository = $customerRepository;
        $this->requestStack = $requestStack;
        $this->cartService = $cartService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            CustomerRegisterEvent::class => 'onCustomerRegister',
            CustomerLoginEvent::class => 'onCustomerLogin',
        ];
    }

    public function onCustomerRegister(CustomerRegisterEvent $event): void
    {
//        $request = $this->requestStack->getCurrentRequest();
//        $schoolCode = $request->request->get('schoolCode');

        $session = $this->requestStack?->getSession();
        $schoolCode = $session?->get('schoolKey');

        $context = $event->getContext();
        if (! $schoolCode) {
            return; // Skip if school code is not provided
        }

        // Find matching customer group by 'school_key' (custom field)
        $context = Context::createDefaultContext();
        $criteria = new Criteria();
        $criteria->setTerm($schoolCode);

        $customerGroup = $this->customerGroupRepository->search($criteria, $context)->first();

        if ($customerGroup) {
            // Assign the customer to the group
            $customerId = $event->getCustomer()->getId();
            $this->customerRepository->update([
                [
                    'id' => $customerId,
                    'groupId' => $customerGroup->getId()
                ]
            ], $context);
            $event->getCustomer()->setGroupId($customerGroup->getId());
        }
    }

    public function onCustomerLogin(CustomerLoginEvent $event): void
    {
        $context = $event->getSalesChannelContext();

        $session = $this->requestStack?->getSession();
        $schoolKey = $session?->get('schoolKey');

        if ($schoolKey) {
            $contextDefault = Context::createDefaultContext();
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('name', $schoolKey)); // Exact match
            $result = $this->customerGroupRepository->search($criteria, $contextDefault);
            $customerGroup = $result->first();

            if ($customerGroup) {
                $context->addExtension('guestCustomerGroup', $customerGroup);
            }
        }

        // Find matching customer group by 'school_key' (custom field)
        $defaultContext = Context::createDefaultContext();
        $criteria = new Criteria();
        $criteria->setTerm($schoolKey);

        $customerGroup = $this->customerGroupRepository->search($criteria, $defaultContext)->first();

        if ($customerGroup) {
            // Assign the customer to the group
            $customerId = $event->getCustomer()->getId();
            $this->customerRepository->update([
                [
                    'id' => $customerId,
                    'groupId' => $customerGroup->getId()
                ]
            ], $defaultContext);
            $context->getCustomer()->setGroupId($customerGroup->getId());
        }

        $guestCustomerGroup = $context->getExtension('guestCustomerGroup');
        $customerGroupId = $context->getCustomer()->getGroupId();
        $guestCustomerGroupId = $guestCustomerGroup ? $guestCustomerGroup->getId() : null;

        if ($guestCustomerGroupId && $guestCustomerGroupId !== $customerGroupId) {
            $this->cartService->deleteCart($context);
        }
    }
}
