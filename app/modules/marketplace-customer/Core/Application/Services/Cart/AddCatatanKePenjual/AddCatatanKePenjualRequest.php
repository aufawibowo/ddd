<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Cart\AddCatatanKePenjual;

class AddCatatanKePenjualRequest
{
    public ?string $cartId;
    public ?string $catatan;

    /**
     * AddCatatanKePenjualRequest constructor.
     * @param string|null $cartId
     * @param string|null $catatan
     */
    public function __construct(?string $cartId, ?string $catatan)
    {
        $this->cartId = $cartId;
        $this->catatan = $catatan;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->cartId))
            $errors[] = 'cartId_must_be_specified';

        if (!isset($this->catatan))
            $errors[] = 'catatan_must_be_specified';

        return $errors;
    }
}