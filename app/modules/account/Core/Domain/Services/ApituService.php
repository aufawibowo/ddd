<?php

namespace A7Pro\Account\Core\Domain\Services;

use A7Pro\Account\Core\Domain\Models\ApituMember;

interface ApituService
{
    public function getMemberByMemberId(string $memberId): ?ApituMember;
}