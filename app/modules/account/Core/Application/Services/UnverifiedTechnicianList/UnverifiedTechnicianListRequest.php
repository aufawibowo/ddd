<?php

namespace A7Pro\Account\Core\Application\Services\UnverifiedTechnicianList;

class UnverifiedTechnicianListRequest
{
    public ?string $userId;

    public function __construct(?string $userId)
    {
        $this->userId = $userId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->userId)) {
            $errors[] = 'user_id_must_be_specified';
        }

        return $errors;
    }
}