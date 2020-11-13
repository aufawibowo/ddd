<?php

namespace A7Pro\Wallet\Core\Domain\Repositories;

use A7Pro\Wallet\Core\Domain\Models\UserId;
use A7Pro\Wallet\Core\Domain\Models\Wallet;

interface WalletRepository
{
    public function getByUserId(UserId $userId): Wallet;
    public function save(Wallet $wallet): bool;
}