<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class OtherAppException extends AppException
{
    public static function databaseError()
    {
        return new self("Error while connect to database server", 201);
    }

    public static function unexpectedError()
    {
        return new self("Unexpected error", 202);
    }

    public static function clientExist()
    {
        return new self("Client exist", 203);
    }

    public static function clientNotExist()
    {
        return new self("Client not exist", 204);
    }

    public static function dateInvalid()
    {
        return new self("Date is invalid", 205);
    }

    public static function invalidTaxRate()
    {
        return new self("Invalid tax rate!", 206);
    }

    public function serialize(): JsonResponse
    {
        if ($this->getCode() === 201 || $this->getCode() === 202) {
            $this->setMessage('database_error');
        }


        if ($this->getCode() === 203) {
            $this->setMessage('client_exist');
        }

        if ($this->getCode() === 204) {
            $this->setMessage('client_not_exist');
        }

        if ($this->getCode() === 205) {
            $this->setMessage('invalid_date');
        }

        if ($this->getCode() === 206) {
            $this->setMessage('invalid_tax_rate');
        }
        return parent::serialize();
    }
}
