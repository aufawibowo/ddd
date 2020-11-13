<?php

namespace A7Pro\Wallet\Core\Domain\Models;

class Invoice
{
    const STATUS_PENDING = "PENDING";
    const STATUS_PAID = "PAID";
    const STATUS_EXPIRED = "EXPIRED";

    private InvoiceId $id;
    private string $code;
    private float $amount;
    private array $detail;
    private string $userId;
    private string $status;
    private ?string $paymentMethod;
    private ?string $externalId;
    private ?Date $expiration;
}