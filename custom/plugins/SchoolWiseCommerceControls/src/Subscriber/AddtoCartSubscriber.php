<?php
declare(strict_types=1);

namespace SchoolWiseCommerceControls\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\Checkout\Cart\Event\BeforeLineItemAddedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;


class AddtoCartSubscriber implements EventSubscriberInterface
{
    private EntityRepository $schoolLicenceRepository;
    private EntityRepository $productRepository;

    public function __construct(
        EntityRepository $schoolLicenceRepository,
        EntityRepository $productRepository
    )
    {
        $this->schoolLicenceRepository = $schoolLicenceRepository;
        $this->productRepository = $productRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeLineItemAddedEvent::class => 'beforeLineItemAdded',
        ];
    }

    public function beforeLineItemAdded(BeforeLineItemAddedEvent $event): void
    {
        $cart = $event->getCart();
        $context = $event->getSalesChannelContext();

        // Get Customer Group ID - check if null, fetch from extension
        $customer = $context->getCustomer();
        if ($customer) {
            $customerGroupId = $context->getCurrentCustomerGroup()->getId();
        } else {
            $guestCustomerGroup = $context->getExtension('guestCustomerGroup');
            $customerGroupId = $guestCustomerGroup?->getId();
        }

        foreach ($cart->getLineItems() as $lineItem) {
            $productId = $lineItem->getReferencedId();
            if (!$productId) {
                continue;
            }

            // Fetch category ID of the product
            $categoryIds = $this->getCategoryId($productId, $context);

            if (!$categoryIds) {
                continue;
            }

            $license = null;
            foreach ($categoryIds as $categoryId) {
                $licenseCriteria = new Criteria();
                $licenseCriteria->addFilter(new EqualsFilter('customerGroupId', $customerGroupId));
                $licenseCriteria->addFilter(new EqualsFilter('categoryId', $categoryId));

                $license = $this->schoolLicenceRepository->search($licenseCriteria, $context->getContext())->first();

                if ($license) {
                    break; // Stop searching once a license is found
                }
            }

            if ($license) {
                $lineItem->setPayloadValue('licenseProductId', $license->getProductId());
            }
        }
    }

    private function getCategoryId(string $productId, $context): ?array
    {
        $criteria = new Criteria([$productId]);
        $criteria->addAssociation('categories');

        $product = $this->productRepository->search($criteria, $context->getContext())->first();

        if (!$product || !$product->getCategories()) {
            return [];
        }

        return array_map(fn($category) => $category->getId(), $product->getCategories()->getElements());
    }
}
