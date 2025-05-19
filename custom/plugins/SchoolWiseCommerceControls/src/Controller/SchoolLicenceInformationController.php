<?php
declare(strict_types=1);

namespace SchoolWiseCommerceControls\Controller;

use Shopware\Core\Framework\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;


#[Route(defaults: ['_routeScope' => ['api']])]
class SchoolLicenceInformationController extends AbstractController
{
    private EntityRepository $schoolLicenceRepository;

    public function __construct(EntityRepository $schoolLicenceRepository)
    {
        $this->schoolLicenceRepository = $schoolLicenceRepository;
    }

    #[Route(path: '/api/school-licences', defaults: ['auth_required' => true], name: 'api.action.school_licences', methods: ['GET'])]
    public function getSchoolLicences(Request $request, Context $context): JsonResponse
    {

        $criteria = new \Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria();
        $schoolLicences = $this->schoolLicenceRepository->search($criteria, $context);

        return new JsonResponse($schoolLicences->getEntities());
    }

    #[Route(path: '/api/create-school-licence', defaults: ['auth_required' => true], name: 'api.school_licences.create', methods: ['POST'])]
    public function createSchoolLicence(Request $request, Context $context): JsonResponse
    {
        $customerGroupId = $request->get('customer_group_id');
        $categoryId = $request->get('category_id');
        $productId = $request->get('product_id');

        $missingFields = [];
        if (!$customerGroupId) {
            $missingFields[] = 'Customer Group';
        }
        if (!$categoryId) {
            $missingFields[] = 'Category';
        }
        if (!$productId) {
            $missingFields[] = 'Product';
        }

        if (count($missingFields) > 0) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customerGroupId', $customerGroupId));
        $criteria->addFilter(new EqualsFilter('categoryId', $categoryId));

        $existingLicence = $this->schoolLicenceRepository->search($criteria, $context)->first();

        if ($existingLicence) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'A record with this Customer Group, Category, and Product combination already exists.'
            ], JsonResponse::HTTP_CONFLICT);
        }

        $data = [
            'id' => Uuid::randomHex(),
            'customerGroupId' => $customerGroupId,
            'categoryId' => $categoryId,
            'categoryVersionId' => $request->get('category_version_id'),
            'productId' => $productId,
            'productVersionId' => $request->get('product_version_id'),
            'createdAt' => new \DateTime(),
        ];

        $this->schoolLicenceRepository->create([$data], $context);

        return new JsonResponse(['status' => 'success', 'message' => 'Record created successfully.']);
    }

    #[Route(path: '/api/update-school-licence/{id}', defaults: ['auth_required' => true], name: 'api.update_school_licence', methods: ['POST'])]
    public function updateSchoolLicenceInformation(string $id, Request $request, Context $context): JsonResponse
    {
        $criteria = new Criteria([strtolower($id)]);
        $existingLicence = $this->schoolLicenceRepository->search($criteria, $context)->first();

        if (!$existingLicence) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Record with the specified ID does not exist.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $customerGroupId = $request->get('customer_group_id');
        $categoryId = $request->get('category_id');
        $productId = $request->get('product_id');

        $missingFields = [];
        if (!$customerGroupId) {
            $missingFields[] = 'Customer Group';
        }
        if (!$categoryId) {
            $missingFields[] = 'Category';
        }
        if (!$productId) {
            $missingFields[] = 'Product';
        }

        if (count($missingFields) > 0) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customerGroupId', $customerGroupId));
        $criteria->addFilter(new EqualsFilter('categoryId', $categoryId));
        $criteria->addFilter(new NotFilter(NotFilter::CONNECTION_AND, [new EqualsFilter('id', strtolower($id))]));

        $existingLicence = $this->schoolLicenceRepository->search($criteria, $context)->first();

        if ($existingLicence) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'A record with this Customer Group, Category, and Product combination already exists.'
            ], JsonResponse::HTTP_CONFLICT);
        }


        $updateData = [
            'id' => strtolower($id),
            'customerGroupId' => $customerGroupId,
            'categoryId' => $categoryId,
            'categoryVersionId' => $request->get('category_version_id'),
            'productId' => $productId,
            'productVersionId' => $request->get('product_version_id'),
            'updatedAt' => new \DateTime(),
        ];

        $updateData = array_filter($updateData, fn($value) => !is_null($value));

        $this->schoolLicenceRepository->update([$updateData], $context);

        return new JsonResponse(['status' => 'success', 'message' => 'Record updated successfully.']);
    }

    #[Route(path: '/api/delete-school-licence/{id}', defaults: ['auth_required' => true], name: 'api.action.delete_school_licence', methods: ['DELETE'])]
    public function deleteSchoolLicenceInformation(string $id, Context $context): JsonResponse
    {
        $criteria = new Criteria([strtolower($id)]);
        $existingLicence = $this->schoolLicenceRepository->search($criteria, $context)->first();

        if (!$existingLicence) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Record with the specified ID does not exist.'
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->schoolLicenceRepository->delete([['id' => strtolower($id)]], $context);

        return new JsonResponse(['status' => 'success', 'message' => 'Record deleted successfully.']);
    }

    #[Route(path: '/api/list-school-licences', defaults: ['auth_required' => true], name: 'api.school_licences.list', methods: ['GET'])]
    public function listSchoolLicences(Request $request, Context $context): JsonResponse
    {
        $customerGroupId = $request->query->get('customer_group_id');
        $categoryId = $request->query->get('category_id');
        $productId = $request->query->get('product_id');


        $criteria = new Criteria();
        $criteria->setLimit((int) $request->query->get('limit', 10)); // Default limit to 10
        $criteria->setOffset((int) $request->query->get('offset', 0));

        if ($customerGroupId) {
            $criteria->addFilter(new EqualsFilter('customerGroupId', $customerGroupId));
        }
        if ($categoryId) {
            $criteria->addFilter(new EqualsFilter('categoryId', $categoryId));
        }
        if ($productId) {
            $criteria->addFilter(new EqualsFilter('productId', $productId));
        }

        $licences = $this->schoolLicenceRepository->search($criteria, $context);

        $data = [];
        foreach ($licences as $licence) {
            $data[] = [
                'id' => $licence->getId(),
                'customerGroupId' => $licence->getCustomerGroupId(),
                'categoryId' => $licence->getCategoryId(),
                'productId' => $licence->getProductId(),
                'createdAt' => $licence->getCreatedAt(),
            ];
        }
        return new JsonResponse([
            'status' => 'success',
            'data' => $data,
            'total' => $licences->getTotal(),
        ]);
    }
}
