<?php

namespace ApiBundle\Exception;

class BrandException extends \Exception
{
    public static function brandWithNameExists()
    {
        return new self("Taka marka już istnieje", 406);
    }

    public static function nameIsTooShort()
    {
        return new self("Nazwa marki jest za krótka", 406);
    }
}
