<?php

namespace A7Pro\Account\Core\Domain\Repositories;

use A7Pro\Account\Core\Domain\Models\DpcId;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Models\UserId;

interface UserRepository
{
    public function getByPhone(string $phone): ?User;
    public function getByEmail(string $email): ?User;
    public function getByUsername(string $username): ?User;
    public function getById(string $id): ?User;
    public function isPhoneExists(string $phone): bool;
    public function isApituMemberIdExists(string $apituMemberId): bool;
    public function getUnverifiedTechnicianInDpc(DpcId $dpcId): array;
    public function getHeadOfDpcList(): array;
    public function getHeadOfDppList(): array;
    public function add(User $user): bool;
    public function update(User $user): bool;
}