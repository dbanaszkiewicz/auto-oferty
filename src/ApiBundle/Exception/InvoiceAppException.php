<?php

namespace ApiBundle\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

class InvoiceAppException extends AppException
{
    public static function invalidExposeDate()
    {
        return new self("Expose date is invalid", 401);
    }

    public static function invalidSaleDate()
    {
        return new self("Sale date is invalid", 402);
    }

    public static function invalidSection()
    {
        return new self("Section is invalid!", 403);
    }

    public static function invalidOrder()
    {
        return new self("Order is invalid!", 404);
    }

    public static function invalidPaymentMethod()
    {
        return new self("Payment method is invalid", 405);
    }

    public static function invoiceNotExist()
    {
        return new self("Invoice not exist", 406);
    }

    public static function cannotCreateInvoice()
    {
        return new self("Invoice cannot be created", 407);
    }

    public static function cannotRemoveInvoice()
    {
        return new self("Cannot cancel invoice!", 408);
    }

    public static function cannotAcceptInvoice()
    {
        return new self("Cannot accept invoice!", 409);
    }

    public function serialize(): JsonResponse
    {
        if ($this->getCode() === 401) {
            $this->setMessage('invalid_expose_date');
        }

        if ($this->getCode() === 402) {
            $this->setMessage('invalid_sale_date');
        }

        if ($this->getCode() === 404) {
            $this->setMessage('invalid_order');
        }

        if ($this->getCode() === 405) {
            $this->setMessage('invalid_payment_method');
        }

        if ($this->getCode() === 403) {
            $this->setMessage('invalid_section');
        }

        if ($this->getCode() === 406) {
            $this->setMessage('invoice_not_exist');
        }

        if ($this->getCode() === 407) {
            $this->setMessage('cannot_create_invoice');
        }

        if ($this->getCode() === 408) {
            $this->setMessage('cannot_cancel_invoice');
        }

        if ($this->getCode() === 409) {
            $this->setMessage('cannot_accept_invoice');
        }

        return parent::serialize();
    }

}
