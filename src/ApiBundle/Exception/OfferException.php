<?php

namespace ApiBundle\Exception;

class OfferException extends ApiException
{
    public static function invalidVersion()
    {
        return new self("Podana wersja jest niepopawna.", 406);
    }
    public static function invalidEquipment()
    {
        return new self("Podane wyposazenie nie istnieje.", 406);
    }
    public static function invalidOffer()
    {
        return new self("Podana oferta nie istnieje.", 406);
    }
    public static function unexpectedUploadError()
    {
        return new self("Wystąpił nieoczekiwany błąd uploadu pliku!", 500);
    }
}