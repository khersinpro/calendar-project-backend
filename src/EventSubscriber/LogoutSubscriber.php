<?php

namespace App\EventSubscriber;

use App\Service\AuthCookieService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutSubscriber implements EventSubscriberInterface
{
    /**
     * On logout event, delete the auth cookie and set the response to no content
     * @param LogoutEvent $event - the logout event
     */
    public function onLogoutEvent(LogoutEvent $event): void
    {
        $response  = new JsonResponse('', JsonResponse::HTTP_NO_CONTENT);
        $response->headers->setCookie(AuthCookieService::deleteAuthCookie());
        $event->setResponse($response);
    }

    /**
     * Get the subscribed events, in this case only the logout event
     * @return array - the subscribed events
     */
    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
