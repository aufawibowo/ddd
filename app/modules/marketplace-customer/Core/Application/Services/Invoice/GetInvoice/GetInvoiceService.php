<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\GetInvoice;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\InvoiceId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\InvoiceRepository;

class GetInvoiceService
{
    private InvoiceRepository $invoiceRepository;

    /**
     * GetInvoiceService constructor.
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function execute(GetInvoiceRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        return $this->invoiceRepository->get(
            new InvoiceId($request->invoiceId)
        );
    }
}