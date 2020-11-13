<?php

namespace A7Pro\Wallet\Core\Domain\Repositories;

use A7Pro\Wallet\Core\Domain\Models\Transaction;

interface TransactionRepository
{
    public function save(Transaction $transaction): bool;
}