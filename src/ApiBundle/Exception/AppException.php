<?php
/**
 * Created by PhpStorm.
 * User: Damian Banaszkiewicz
 * 03.07.18 20:01
 */

namespace ApiBundle\Exception;


use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AppException extends \Exception
{
    /**
     * @return JsonResponse
     */
    public function serialize() : JsonResponse
    {
        return new JsonResponse([
            "Error" => [
                "code" => $this->code,
                "message" => $this->message
            ]
        ]);
    }

    protected function setMessage($message) {
        $this->message = $message;
    }
}
