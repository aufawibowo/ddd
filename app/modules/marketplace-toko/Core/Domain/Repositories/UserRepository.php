<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Repositories;

use A7Pro\Marketplace\Toko\Core\Domain\Models\User;

interface UserRepository
{
    public function getByPhone(string $phone): ?User;
    public function getByEmail(string $email): ?User;
    public function getByUsername(string $username): ?User;
    public function isPhoneExists(string $phone): bool;
}
