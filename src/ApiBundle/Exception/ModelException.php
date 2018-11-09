<?php

namespace ApiBundle\Exception;

class ModelException extends \Exception
{
    public static function modelWithNameExists()
    {
        return new self("Taki model już istnieje", 406);
    }
}
