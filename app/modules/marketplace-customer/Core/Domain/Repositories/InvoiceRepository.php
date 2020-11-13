<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Repositories;

use A7Pro\Marketplace\Customer\Core\Domain\Models\InvoiceId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Invoice;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;

interface InvoiceRepository
{
    public function save(Invoice $invoice, Order $order): bool;
    public function get(InvoiceId $invoiceId);
}