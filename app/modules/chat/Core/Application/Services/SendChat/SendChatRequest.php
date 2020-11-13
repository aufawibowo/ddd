<?php

namespace A7Pro\Chat\Core\Application\Services\SendChat;

class SendChatRequest
{
    public ?string $userId;
    public ?string $receiverId;
    public ?string $message;

    public function __construct(?string $userId, ?string $receiverId, ?string $message)
    {
        $this->userId = $userId;
        $this->receiverId = $receiverId;
        $this->message = $message;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->userId))
            $errors[] = 'user_id_must_be_specified';

        if (!isset($this->receiverId))
            $errors[] = 'receiver_id_must_be_specified';

        if ($this->receiverId == $this->userId)
            $errors[] = 'receiver_id_must_be_specified';

        if (!isset($this->message))
            $errors[] = 'message_must_be_specified';

        return $errors;
    }
}
