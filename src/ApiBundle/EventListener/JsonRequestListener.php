<?php

namespace ApiBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event;

/**
 * Services json requests
 */
class JsonRequestListener
{
    /**
     * Determines if json request was been invalid
     * @var bool
     */
    private $invalidJsonRequest = false;

    public function __construct()
    {
        /**
         * For defaults set as valid json request
         */
        $this->invalidJsonRequest = false;
    }

    /**
     * Listener for kernel request event
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $requestEvent
     */
    public function onKernelRequest(Event\GetResponseEvent $requestEvent)
    {
        /**
         * Gets request object
         */
        $request = $requestEvent->getRequest();
        /**
         * Gets request Content-Type
         */
        $contentType = $request->headers->get('Content-Type');
        /**
         * If contentType is application/json
         */
        if (strpos($contentType, 'application/json') === 0) {
            /*
             * Gets request's body
             */
            $requestData = json_decode($request->getContent(), true);
            /**
             * If datas are correct
             */
            if (is_array($requestData)) {
                $request->request->replace($requestData);
            } else {
                $this->invalidJsonRequest = true;
            }
        }
    }

    /**
     * Listener for kernel response event.
     * Sets http 400 code when json request was invalid
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $responseEvent
     */
    public function onKernelResponse(Event\FilterResponseEvent $responseEvent)
    {
        /**
         * Gets response event
         */
        $response = $responseEvent->getResponse();
        $response->headers->set('Content-Type', $response->headers->get('Content-Type') . '; charset=UTF-8');

        if ($response instanceof JsonResponse) {
            $response->setEncodingOptions(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

        /**
         * If json request was invalid
         */
        if ($this->invalidJsonRequest) {
            /**
             * Sets HTTP 400 Bad Request status code
             */
            $response->setStatusCode(400);
        }
    }
}
