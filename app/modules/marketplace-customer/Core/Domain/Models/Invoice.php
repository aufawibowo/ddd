<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


class Invoice
{
    private InvoiceId $invoiceId;
    private string $code;
    private string $status;
    private string $paymentMethod;
    private string $expiration;
    private string $shippingAddress;
    private string $customerId;
    
    public function __construct(
        InvoiceId $invoiceId,
        string $code,
        string $status,
        string $paymentMethod,
        string $expiration,
        string $shippingAddress,
        string $customerId
    ){
        $this->invoiceId = $invoiceId;
        $this->code = $code;
        $this->status = $status;
        $this->expiration = $expiration;
        $this->paymentMethod = $paymentMethod;
        $this->expiration = $expiration;
        $this->shippingAddress = $shippingAddress;
        $this->customerId = $customerId;
    }

    /**
     * @return string
     */
    public function getInvoiceId(): string
    {
        return $this->invoiceId->id();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getExpiration(): string
    {
        return $this->expiration;
    }

    /**
     * @return string
     */
    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    /**
     * @return string
     */
    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->paymentMethod))
            $errors[] = 'payment_method_must_be_specified';

        if (!isset($this->shippingAddress))
            $errors[] = 'shipping_address_must_be_specified';

        if (!isset($this->expiration))
            $errors[] = 'expiration_must_be_specified';

        return $errors;
    }
}