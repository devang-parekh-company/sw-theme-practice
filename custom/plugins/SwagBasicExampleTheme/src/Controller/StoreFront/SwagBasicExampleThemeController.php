<?php

declare(strict_types=1);

namespace SwagBasicExampleTheme\Controller\StoreFront;

use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class SwagBasicExampleThemeController extends StorefrontController
{

    #[Route(
        path: '/theme-page',
        name: 'frontend.theme.page',
        methods: ['GET'],
        defaults: ['_routeScope' => ['storefront']]
    )]
    public function getBlogInfo(): Response
    {
        return $this->renderStorefront('@SwagBasicExampleTheme/storefront/layout/test.html.twig', [
            'example' => 'Hello world devang'
        ]);
    }
}
