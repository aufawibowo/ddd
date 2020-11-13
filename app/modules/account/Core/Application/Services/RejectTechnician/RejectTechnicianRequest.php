<?php

namespace A7Pro\Account\Core\Application\Services\RejectTechnician;

class RejectTechnicianRequest
{
    public ?string $userId;
    public ?string $technicianId;

    public function __construct(?string $userId, ?string $technicianId)
    {
        $this->userId = $userId;
        $this->technicianId = $technicianId;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->userId)) {
            $errors[] = 'user_id_must_be_specified';
        }

        if (!isset($this->technicianId)) {
            $errors[] = 'technician_id_must_be_specified';
        }

        return $errors;
    }
}