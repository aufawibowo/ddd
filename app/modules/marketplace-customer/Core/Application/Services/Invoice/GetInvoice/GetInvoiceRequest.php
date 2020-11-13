<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\GetInvoice;

class GetInvoiceRequest
{
    public ?string $invoiceId;

    /**
     * GetInvoiceRequest constructor.
     * @param string|null $invoiceId
     */
    public function __construct(?string $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function validate()
    {
        $errors = [];

        if (!isset($this->invoiceId))
            $errors[] = 'invoiceId_must_be_specified';

        return $errors;
    }
}