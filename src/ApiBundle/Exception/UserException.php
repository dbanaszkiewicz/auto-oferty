<?php

namespace ApiBundle\Exception;

class UserException extends ApiException
{
    public static function invalidLoginData()
    {
        return new self("Podane dane logowania są nieprawidłowe", 406);
    }

    public static function invalidEmail()
    {
        return new self("Podany adres e-mail jest nieprawidłowy", 406);
    }

    public static function invalidFirstName()
    {
        return new self("Podane imię jest nieprawidłowe", 406);
    }

    public static function invalidPassword()
    {
        return new self("Podane hasło jest niepoprawne", 406);
    }

    public static function userWithEmailExist()
    {
        return new self("Konto z tym adresem e-mail jest już zarejestrowane", 406);
    }

    public static function userIsNotLogged()
    {
        return new self("Nie jesteś zalogowany", 405);
    }
}
