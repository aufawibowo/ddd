<?php

namespace A7Pro\Marketplace\Toko\Core\Application\Services\ShowSellerProfileById;

class ShowSellerProfileByIdRequest
{
    public ?string $sellerId;
    public ?string $profileId;

    public function __construct(
        ?string $sellerId,
        ?string $profileId
    ) {
        $this->sellerId = $sellerId;
        $this->profileId = $profileId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->sellerId))
            $errors[] = 'seller_id_must_be_specified';

        if (!isset($this->profileId))
            $errors[] = 'profile_id_must_be_specified';

        return $errors;
    }
}
