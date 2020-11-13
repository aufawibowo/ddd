<?php

namespace A7Pro\Marketplace\Customer\Core\Application\Services\Profile\ShowProfile;

use A7Pro\Marketplace\Customer\Core\Domain\Models\CustomerId;

class ShowProfileRequest
{
    public string $customerId;

    /**
     * ShowProfileRequest constructor.
     * @param CustomerId $customerId
     */
    public function __construct(string $customerId)
    {
        $this->customerId = $customerId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->customerId)) {
            $errors[] = 'customer_id_must_specified';
        }
        return $errors;
    }

}