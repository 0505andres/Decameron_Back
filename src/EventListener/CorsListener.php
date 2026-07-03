<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

final class CorsListener
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        if (0 !== strpos($request->getPathInfo(), '/api')) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $response->headers->set('Access-Control-Max-Age', '3600');

        if ($request->getMethod() === 'OPTIONS') {
            $event->setResponse(new Response('', Response::HTTP_NO_CONTENT, $response->headers->all()));
        }
    }
}
