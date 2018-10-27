<?php

namespace ApiBundle\Exception;

class ApiException extends \Exception
{
    public static function methodNotExists()
    {
        return new self("Zasób nie istnieje", 404);
    }
}
