<?php

namespace A7Pro\Chat\Core\Domain\Repositories;

use A7Pro\Chat\Core\Domain\Models\User;

interface UserRepository
{
    public function getUserById(string $userId): User;
}