<?php

namespace A7Pro\Wallet\Core\Domain\Models;

use A7Pro\Wallet\Core\Domain\Exceptions\InvalidOperationException;

class Wallet
{
    private WalletId $id;
    private UserId $userId;
    private float $balance;
    private float $hold;
    private float $saving;

    public function __construct(
        WalletId $id,
        UserId $userId,
        float $balance,
        float $hold,
        float $saving
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->balance = $balance;
        $this->hold = $hold;
        $this->saving = $saving;
    }

    public function getId(): WalletId
    {
        return $this->id;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getHold(): float
    {
        return $this->hold;
    }

    public function getSaving(): float
    {
        return $this->saving;
    }

    public function creditBalance(float $amount)
    {
        $this->balance = $this->balance + $amount;
    }

    public function debitBalance(float $amount)
    {
        if ($this->balance < $amount)
            throw new InvalidOperationException('insufficient_balance');

        $this->balance = $this->balance - $amount;
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