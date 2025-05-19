<?php

declare(strict_types=1);

namespace ZenitPlatformGravityChild\Controller\Api;

use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Log\Package;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]

class CustomController extends AbstractController
{
    // Add return type declaration
    #[Route(path: '/get-customer-group', name: 'api.custom.customer-group.list', methods: ['GET'])]
    public function getCustomerGroup(Request $request, Context $context): JsonResponse
    {
        try {
            $blogRepository = $this->container->get('customer_group.repository');
            $criteria = new Criteria();
            $term = $request->get('term', '');
            $criteria->setTerm($term);
            $result = $blogRepository->search($criteria, $context);
            $data = [];

            foreach ($result->getEntities() as $customerGroup) {
                $data[] = [
                    'id' => $customerGroup->id,
                    'name' => $customerGroup->name,
                ];
            }
            return new JsonResponse(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
