<?php

namespace A7Pro\Wallet\Core\Application\Services\Pay;

class PayRequest
{
    public ?string $senderId;
    public ?string $receiverId;
    public ?float $amount;
    public ?string $description;

    public function __construct(?string $senderId, ?string $receiverId, ?float $amount, ?string $description)
    {
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->amount = $amount;
        $this->description = $description;
    }


    public function validate()
    {
        $errors = [];

        if (!isset($this->senderId)) {
            $errors[] = 'sender_id_must_be_specified';
        }

        if (!isset($this->receiverId)) {
            $errors[] = 'receiver_id_must_be_specified';
        }

        if (!isset($this->amount) || $this->amount <= 0) {
            $errors[] = 'amount_must_be_specified';
        }

        return $errors;
    }
}