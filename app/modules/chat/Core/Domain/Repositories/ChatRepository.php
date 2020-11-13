<?php

namespace A7Pro\Chat\Core\Domain\Repositories;

use A7Pro\Chat\Core\Domain\Models\Chat;

interface ChatRepository
{
    public function getHistoryByUserId(string $userId, string $receiverId, int $page, int $limit): array;
    public function isReceiverExist(string $senderId, string $receiverId): ?string;
    public function getChatList(string $userId, int $isUnread, int $isUnreplied): array;
    public function save(Chat $chat): bool;
}
