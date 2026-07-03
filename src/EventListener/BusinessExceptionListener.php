<?php

namespace App\EventListener;

use App\Exception\BusinessException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class BusinessExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 10],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();
        if ($e instanceof BusinessException) {
            $response = new JsonResponse([
                'error' => 'business_error',
                'message' => $e->getMessage(),
            ], 400);

            $event->setResponse($response);
        }
    }
}
