<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Storefront\Controller;


use Shopware\Core\Checkout\Cart\LineItemFactoryHandler\ProductLineItemFactory;
use Shopware\Core\Checkout\Cart\LineItemFactoryRegistry;
use Shopware\Core\Framework\Context;
use Shopware\Core\Checkout\Promotion\Cart\PromotionItemBuilder;
use Shopware\Core\Content\Product\SalesChannel\AbstractProductListRoute;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Util\HtmlSanitizer;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Storefront\Controller\CartLineItemController;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;

#[\Symfony\Component\Routing\Attribute\Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class CartLineItemControllerDecorator extends CartLineItemController
{

    public function __construct(
        private readonly CartService              $cartService,
        private readonly PromotionItemBuilder     $promotionItemBuilder,
        private readonly ProductLineItemFactory   $productLineItemFactory,
        private readonly HtmlSanitizer            $htmlSanitizer,
        private readonly AbstractProductListRoute $productListRoute,
        private readonly LineItemFactoryRegistry  $lineItemFactoryRegistry,
        private CartLineItemController            $decorated,
        private readonly EntityRepository         $schoolLicenceRepository,
        private readonly EntityRepository         $productRepository,
        private readonly EntityRepository         $customerGroupRepository,
    )
    {
    }

    public function getDecorated(): CartLineItemController
    {
        return $this->decorated;
    }

    public function addLineItems(Cart $cart, RequestDataBag $requestDataBag, Request $request, SalesChannelContext $context): Response
    {

        $session = $request?->getSession();
        $schoolKey = $session?->get('schoolKey');
        if ($schoolKey) {
            $contextDefault = Context::createDefaultContext();
            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('name', $schoolKey)); // Exact match
            $result = $this->customerGroupRepository->search($criteria, $contextDefault);
            $customerGroup = $result->first();
            $context->addExtension('guestCustomerGroup', $customerGroup);
        }

        // Get Customer Group ID - check if null, fetch from extension
        $customer = $context->getCustomer();
        if ($customer) {
            $customerGroupId = $context->getCurrentCustomerGroup()->getId();
        } else {
            $guestCustomerGroup = $context->getExtension('guestCustomerGroup');
            $customerGroupId = $guestCustomerGroup?->getId();
        }
        if (!$customerGroupId) {
            return $this->decorated->addLineItems($cart, $requestDataBag, $request, $context);
        }

        $lineItems = $requestDataBag->get('lineItems');
        if (!$lineItems instanceof RequestDataBag) {
            return $this->decorated->addLineItems($cart, $requestDataBag, $request, $context);
        }

        if ($requestDataBag->get('repeatTo')) {
                $filteredArray = array_filter($lineItems->all(), function ($lineItem) {
                    return !array_key_exists('isLicenseProduct', $lineItem);
                });
            $requestDataBag->set('lineItems', $filteredArray);

            $response = $this->decorated->addLineItems($cart, $requestDataBag, $request, $context);
        }else{
            $response = $this->decorated->addLineItems($cart, $requestDataBag, $request, $context);
        }

        foreach ($lineItems->all() as $lineItem) {
            if (!isset($lineItem['id'])) {
                continue;
            }

            $productId = $lineItem['id'];

            $categoryIds = $this->getCategoryId($productId, $context);
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
                $licenseProductId = $license->getProductId();

                $licenseLineItem = (new LineItem($licenseProductId, LineItem::PRODUCT_LINE_ITEM_TYPE, $licenseProductId, (int)$lineItem['quantity']))
                    ->setRemovable(true)
                    ->setPayload(['isLicenseProduct' => true])
                    ->setStackable(true);
                $this->cartService->add($cart, $licenseLineItem, $context);
            }

        }
        return $response;
    }

    public function deleteLineItem(Cart $cart, string $id, Request $request, SalesChannelContext $context): Response
    {
        $lineItem = $cart->getLineItems()->get($id);

        if (!$lineItem) {
            return $this->decorated->deleteLineItem($cart, $id, $request, $context);
        }

        // Check if the product being removed is linked to a license product
        $licenseProductId = $lineItem->getPayloadValue('licenseProductId');
        $currentQuantity = $lineItem->getQuantity();

        $response = $this->decorated->deleteLineItem($cart, $id, $request, $context);

        if ($licenseProductId) {
            $licenseLineItem = $cart->getLineItems()->get($licenseProductId);

            if ($licenseLineItem) {
                // Update the quantity of the license product
                $newQuantity = $licenseLineItem->getQuantity() - $currentQuantity;

                // If the new quantity is greater than 0, update the license product
                if ($newQuantity > 0) {
                    $licenseLineItem->setQuantity($newQuantity);
                } else {
                    // If the quantity is 0 or less, remove the license product
                    $cart->remove($licenseLineItem->getId());
                }

                // Recalculate the cart
                $this->cartService->recalculate($cart, $context);
            }
        }

        return $response;
    }

    public function deleteLineItems(Cart $cart, Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->deleteLineItems($cart, $request, $context);
    }

    public function addPromotion(Cart $cart, Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->addPromotion($cart, $request, $context);
    }

    public function changeQuantity(Cart $cart, string $id, Request $request, SalesChannelContext $context): Response
    {
        $lineItem = $cart->getLineItems()->get($id);

        if (!$lineItem) {
            return $this->decorated->changeQuantity($cart, $id, $request, $context);
        }

        $licenseProductId = $lineItem->getPayloadValue('licenseProductId');
        $currentQuantity = $lineItem->getQuantity();
        $newQuantity = (int) $request->get('quantity', $currentQuantity);

        $response = $this->decorated->changeQuantity($cart, $id, $request, $context);

        if ($licenseProductId) {
            $licenseLineItem = $cart->getLineItems()->get($licenseProductId);

            if ($licenseLineItem) {
                // Calculate the difference in quantity for the main product
                $quantityDifference = $newQuantity - $currentQuantity;

                // Adjust the license product quantity based on this difference
                $licenseLineItem->setQuantity($licenseLineItem->getQuantity() + $quantityDifference);

                // Recalculate the cart
                $this->cartService->recalculate($cart, $context);
            }
        }

        return $response;
    }

    public function updateLineItems(Cart $cart, RequestDataBag $requestDataBag, Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->updateLineItems($cart, $requestDataBag, $request, $context);
    }

    public function addProductByNumber(Request $request, SalesChannelContext $context): Response
    {
        return $this->decorated->addProductByNumber($request, $context);
    }

    private function getCategoryId(string $productId, SalesChannelContext $context): ?array
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
