<?php

namespace A7Pro\Chat\Core\Application\Services\GetChatList;

class GetChatListRequest
{
    public ?string $userId;
    public ?int $isUnread;
    public ?int $isUnreplied;

    public function __construct(?string $userId, ?int $isUnread, ?int $isUnreplied)
    {
        $this->userId = $userId;
        $this->isRead = $isUnread;
        $this->isUnreplied = $isUnreplied;
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->userId))
            $errors[] = 'user_id_must_be_specified';

        return $errors;
    }
}
