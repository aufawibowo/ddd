<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;

use A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\GetInvoice\GetInvoiceRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\GetInvoice\GetInvoiceService;
use Exception;

class InvoiceController extends BaseController
{
    public function getAction()
    {
        $invoice_id = $this->request->get('invoice_id');

        $request = new GetInvoiceRequest($invoice_id);

        $service = new GetInvoiceService($this->di->get('invoiceRepository'));

        try {
            $results = $service->execute($request);

            $this->sendData($results);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}