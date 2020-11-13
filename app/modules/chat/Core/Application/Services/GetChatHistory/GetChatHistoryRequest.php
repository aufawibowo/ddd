<?php

namespace A7Pro\Chat\Core\Application\Services\GetChatHistory;

class GetChatHistoryRequest
{
    public ?string $userId;
    public ?string $receiverId;
    public ?int $page;
    public ?int $limit;

    public function __construct(?string $userId, ?string $receiverId, ?int $page, ?int $limit)
    {
        $this->userId = $userId;
        $this->receiverId = $receiverId;
        $this->page = $page ?: 0;
        $this->limit = $limit ?: 0;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->userId))
            $errors[] = 'user_id_must_be_specified';

        if (!isset($this->receiverId))
            $errors[] = 'receiver_id_must_be_specified';

        return $errors;
    }
}
