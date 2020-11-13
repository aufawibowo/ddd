<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Invoice\CreateInvoice;

use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Customer\Core\Domain\Exceptions\ValidationException;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Invoice;
use A7Pro\Marketplace\Customer\Core\Domain\Models\InvoiceId;
use A7Pro\Marketplace\Customer\Core\Domain\Models\Order;
use A7Pro\Marketplace\Customer\Core\Domain\Models\OrderId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\CartRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\InvoiceRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductRepository;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProfileRepository;

class CreateInvoiceService
{
    private InvoiceRepository $invoiceRepository;
    private ProfileRepository $profileRepository;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    /**
     * CreateInvoiceService constructor.
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
        ProfileRepository $profileRepository,
        CartRepository $cartRepository,
        ProductRepository $productRepository
    ){
        $this->invoiceRepository = $invoiceRepository;
        $this->profileRepository = $profileRepository;
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(CreateInvoiceRequest $request)
    {
        $errors = $request->validate();

        if (count($errors) > 0)
            throw new ValidationException($errors);

        $invoiceId = new InvoiceId();

        // get address by id
        $shippingAddress =
            $this->profileRepository->getAddressById(
                $request->shippingAddress
            );

        if(is_null($shippingAddress))
            throw new InvalidOperationException('Alamat pengiriman tidak ditemukan.');

        // check products stock
        $checkProductStock = $this->cartRepository->checkProductStock($request->cartIds);
        if(count($checkProductStock) > 0)
            throw new InvalidOperationException(
                "Stok produk tidak mencukupi " . json_encode($checkProductStock)
            );

        $invoice = new Invoice(
            $invoiceId,
            "INV/".date("d/m/Y"),
            "UNPAID",
            $request->paymentMethod,
            date("Y-m-d H:i:s", time() + 86400),
            $shippingAddress,
            $request->customerId
        );

        // validate invoice
        $errors = $invoice->validate();

        if(count($errors))
            throw new ValidationException($errors);

        $order = new Order(
            new OrderId(),
            $invoiceId->id(),
            $request->customerId,
            null,
            $request->courierIds,
            Order::STATUS_ONORDER, // status
            $request->cartIds,
            $request->productId
        );

        // validate orders
        $errors = $order->validate();

        if(count($errors))
            throw new ValidationException($errors);

        $save = $this->invoiceRepository->save($invoice, $order);

        if(!$save)
            throw new InvalidOperationException('somethings\'s wrong');

        return $save;
    }
}