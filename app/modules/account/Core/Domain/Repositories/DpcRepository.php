<?php

namespace A7Pro\Account\Core\Domain\Repositories;

use A7Pro\Account\Core\Domain\Models\Dpc;

interface DpcRepository
{
    public function getByCode(string $code): ?Dpc;
}