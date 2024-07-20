<?php

namespace App\EventSubscriber;

use App\Service\AuthCookieService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    public function onLogoutEvent(LogoutEvent $event): void
    {
        $response  = new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
        $response->headers->setCookie(AuthCookieService::deleteAuthCookie());
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
