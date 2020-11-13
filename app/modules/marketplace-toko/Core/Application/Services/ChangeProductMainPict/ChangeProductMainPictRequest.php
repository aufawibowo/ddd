<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ChangeProductMainPict;

class ChangeProductMainPictRequest
{
    public ?string $sellerId;
    public ?string $pictId;

    public function __construct(
        ?string $sellerId,
        ?string $pictId
    ) {
        $this->sellerId = $sellerId;
        $this->pictId = $pictId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->pictId)) {
            $errors[] = 'pict_id_must_be_specified';
        }

        return $errors;
    }
}
