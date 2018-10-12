<?php
/**
 * Created by PhpStorm.
 * User: Damian Banaszkiewicz
 * 03.07.18 20:52
 */

namespace ApiBundle\Exception;


use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionHandler
{
    /**
     * @return JsonResponse
     */
    static public function serialize(\Exception $ex) : JsonResponse
    {
        if ($ex instanceof AppException) {
            return $ex->serialize();
        }

        return new JsonResponse([
            "Error" => [
                "code" => 0,
                "message" => 'fatal-application-error'
            ]
        ]);
    }
}