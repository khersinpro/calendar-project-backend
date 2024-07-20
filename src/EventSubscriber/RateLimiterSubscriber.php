<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimiterSubscriber implements EventSubscriberInterface
{
    public function __construct(private RateLimiterFactory $loginLimiter)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        //  Login rate limiter
        if ($request->getPathInfo() === '/api/auth/login') {
            $limiter = $this->loginLimiter->create($request->getClientIp());
            
            if (false === $limiter->consume(1)->isAccepted()) {
                throw new \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException();
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
