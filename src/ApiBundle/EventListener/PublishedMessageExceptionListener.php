<?php

namespace ApiBundle\EventListener;

use ApiBundle\Exception\ApiException;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class PublishedMessageExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof ApiException) {
            $responseData = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            ];
            $event->setResponse(new JsonResponse($responseData, $responseData['error']['code']));
        }

        if (!in_array(@$_SERVER['REMOTE_ADDR'], ['172.20.0.1', '::1'])) {
            $responseData = [
                'error' => [
                    'code' => 500,
                    'message' => "Internal server error"
                ]
            ];
            $event->setResponse(new JsonResponse($responseData, $responseData['error']['code']));
        }
    }
}
