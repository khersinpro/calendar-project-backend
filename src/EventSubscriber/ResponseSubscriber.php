<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(private CsrfTokenManagerInterface $csrfTokenManager)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->set('X-CSRF-TOKEN', $this->csrfTokenManager->getToken('auth'));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
