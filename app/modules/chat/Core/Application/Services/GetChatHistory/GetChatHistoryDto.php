<?php

namespace A7Pro\Chat\Core\Application\Services\GetChatHistory;

use A7Pro\Chat\Core\Domain\Models\Chat;

class GetChatHistoryDto
{
    public $data;
    public $userId;

    public function __construct(array $chatList, string $userId)
    {
        $this->userId = $userId;
        $this->data = $this->transformChatHistory($chatList);
    }

    public function transformChatHistory(array $chatList)
    {
        $data = [];

        foreach ($chatList as $chat) {
            $data[] = $this->transformChat($chat);
        }

        return $data;
    }

    private function transformChat(Chat $chat)
    {
        $obj = new \stdClass();
        $obj->message = $chat->getMessage();
        $obj->status = $chat->getMessageStatus($this->userId);
        $obj->sent_at = $chat->getCreatedAt()->toIsoDateTimeString();

        return $obj;
    }
}