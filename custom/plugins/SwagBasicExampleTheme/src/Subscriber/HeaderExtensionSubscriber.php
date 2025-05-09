<?php

namespace SwagBasicExampleTheme\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class HeaderExtensionSubscriber implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $response = $event->getResponse();
        $content = $response->getContent();

        if (!str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return;
        }

        $customHeader = $this->twig->render('storefront/layout/header/header.html.twig');

        $content = preg_replace('/<body[^>]*>/', '$0' . $customHeader, $content);

        $response->setContent($content);
    }
}
