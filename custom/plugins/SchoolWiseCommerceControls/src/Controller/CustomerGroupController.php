<?php declare(strict_types=1);

namespace SchoolWiseCommerceControls\Controller;

use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route(defaults: ['_routeScope' => ['storefront']])]
#[Package('storefront')]
class CustomerGroupController
{
    private EntityRepository $customerGroupRepository;
    private RouterInterface $router;

    public function __construct(EntityRepository $customerGroupRepository, RouterInterface $router)
    {
        $this->customerGroupRepository = $customerGroupRepository;
        $this->router = $router;
    }

    #[Route(path: '/school-codes', name: 'frontend.school_code.search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query->get('term', '');

        // Query customer groups matching the search term
        $context = Context::createDefaultContext();
        $criteria = new Criteria();
        $criteria->setTerm($searchTerm);

        $result = $this->customerGroupRepository->search($criteria,$context);
        $data = [];
        foreach ($result->getEntities() as $customerGroup) {
            $data[] = [
                'id' => $customerGroup->getId(),
                'name' => $customerGroup->getTranslated()['name'],
            ];
        }
        return new JsonResponse($data);
    }

    #[Route(path: '/validate-school-key', name: 'frontend.school_key.validate', methods: ['POST'])]
    public function validateSchoolKey(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $schoolKey = trim($data['schoolKey'] ?? '');

        if (!$schoolKey) {
            return new JsonResponse(['valid' => false, 'message' => 'School key is required'], 400);
        }

        // Validate if the school key exactly matches a customer group name
        $context = Context::createDefaultContext();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $schoolKey)); // Exact match

        $result = $this->customerGroupRepository->search($criteria, $context);
        $customerGroup = $result->first();

        if (!$customerGroup) {
            return new JsonResponse(['valid' => false, 'message' => 'Invalid school key'], 400);
        }

        return new JsonResponse([
            'valid' => true,
            'message' => 'Valid school key',
            'schoolKey' => $schoolKey
        ]);
    }

    #[Route(path: '/store-school-key', name: 'frontend.school_key.store', methods: ['POST'])]
    public function storeSchoolKey(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $schoolKey = $data['schoolKey'] ?? '';

        if (!$schoolKey) {
            return new JsonResponse(['error' => 'School key is required'], 400);
        }

        $session = $request->getSession();
        $session->set('schoolKey', $schoolKey);

        return new JsonResponse([
            'success' => true,
            'message' => 'School key stored',
            'redirectUrl' => $this->router->generate('frontend.home.page') // Generate the frontend URL
        ]);
    }


}
