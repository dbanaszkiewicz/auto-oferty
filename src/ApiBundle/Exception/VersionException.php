<?php

namespace ApiBundle\Exception;

class VersionException extends \Exception
{
    public static function versionWithNameExists()
    {
        return new self("Taka wersja już istnieje", 406);
    }
}
