<?php

namespace A7Pro\Wallet\Core\Domain\Models;

class Transaction
{
    const TYPE_TOP_UP = 'TOP_UP';
    const TYPE_PAY = 'PAY';

    private TransactionId $id;
    private TransactionCode $code;
    private string $description;
    private Date $date;
    private WalletId $creditedWallet;
    private WalletId $debitedWallet;
    private float $amount;
    private string $type;

    public function __construct(
        TransactionId $id,
        TransactionCode $code,
        string $description,
        Date $date,
        WalletId $creditedWallet,
        WalletId $debitedWallet,
        float $amount,
        string $type
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->description = $description;
        $this->date = $date;
        $this->creditedWallet = $creditedWallet;
        $this->debitedWallet = $debitedWallet;
        $this->amount = $amount;
        $this->type = $type;
    }

    public function getId(): TransactionId
    {
        return $this->id;
    }

    public function getTransactionCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDate(): Date
    {
        return $this->date;
    }

    public function getCreditedWallet(): WalletId
    {
        return $this->creditedWallet;
    }

    public function getDebitedWallet(): WalletId
    {
        return $this->debitedWallet;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function validate(): array
    {
        $errors = [];

        if (!$this->id->isValid()) {
            $errors[] = 'invalid_id';
        }

        return $errors;
    }
}