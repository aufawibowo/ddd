<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProductPhotoById;

class DeleteProductPhotoByIdRequest
{
    public ?string $photoId;
    public ?string $sellerId;

    public function __construct(
        ?string $photoId,
        ?string $sellerId
    ) {
        $this->sellerId = $sellerId;
        $this->photoId = $photoId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId)) {
            $errors[] = 'seller_id_must_be_specified';
        }

        if (!isset($this->photoId)) {
            $errors[] = 'photo_id_must_be_specified';
        }

        return $errors;
    }
}
