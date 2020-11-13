<?php

namespace A7Pro\Account\Core\Application\Services\SetHeadOfDpc;

class SetHeadOfDpcRequest
{
    public ?string $authUserId;
    public ?string $id;
    public bool $isSearch;

    public function __construct(?string $authUserId, ?string $id, bool $isSearch)
    {
        $this->authUserId = $authUserId;
        $this->id = $id;
        $this->isSearch = $isSearch;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->authUserId)) {
            $errors[] = 'auth_user_id_must_be_specified';
        }

        if (!isset($this->id)) {
            $errors[] = 'id_must_be_specified';
        }

        return $errors;
    }
}