<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class UserAppException extends AppException
{
    public static function invalidPassword()
    {
        return new self("Password is invalid", 101);
    }

    public static function invalidNip()
    {
        return new self("Nip is invalid", 102);
    }

    public static function userNotLogged()
    {
        return new self("User is not logged", 103);
    }

    public static function userIsLogged()
    {
        return new self("User is Logged", 104);
    }

    public static function passwordTooShort()
    {
        return new self("Password is invalid", 105);
    }

    public static function userExist()
    {
        return new self("User is exist", 106);
    }

    public static function userNotExist()
    {
        return new self("User is not exist", 108);
    }

    public static function userWithNipExist()
    {
        return new self("User with this NIP exist!", 109);
    }

    public function serialize(): JsonResponse
    {
        if ($this->getCode() === 102) {
            $this->setMessage('nip_is_invalid');
        }

        if ($this->getCode() === 101) {
            $this->setMessage('invalid_password');
        }

        if ($this->getCode() === 104) {
            $this->setMessage('user_is_logged');
        }

        if ($this->getCode() === 103) {
            $this->setMessage('user_is_not_logged');
        }

        if ($this->getCode() === 105) {
            $this->setMessage('password_too_short');
        }

        if ($this->getCode() === 106) {
            $this->setMessage('user_exist');
        }

        if ($this->getCode() === 108) {
            $this->setMessage('user_not_exist');
        }

        if ($this->getCode() === 109) {
            $this->setMessage('user_with_this_nip_exist');
        }

        return parent::serialize();
    }
}
