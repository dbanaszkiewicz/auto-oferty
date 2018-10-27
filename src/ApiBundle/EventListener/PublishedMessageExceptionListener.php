<?php

namespace ApiBundle\EventListener;

use ApiBundle\Exception\ApiException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class PublishedMessageExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $responseData = [];
        if ($exception instanceof ApiException) {
            $responseData = [
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage()
                ]
            ];
        } else {
            $responseData = [
                'error' => [
                    'code' => 500,
                    'message' => "Internal server error"
                ]
            ];
        }

        $event->setResponse(new JsonResponse($responseData, $responseData['error']['code']));
    }
}
