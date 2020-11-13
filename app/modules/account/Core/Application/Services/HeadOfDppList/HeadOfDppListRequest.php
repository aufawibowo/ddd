<?php

namespace A7Pro\Account\Core\Application\Services\HeadOfDppList;

class HeadOfDppListRequest
{
    public ?string $authUserId;

    public function __construct(?string $authUserId)
    {
        $this->authUserId = $authUserId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->authUserId)) {
            $errors[] = 'auth_user_id_must_be_specified';
        }

        return $errors;
    }
}