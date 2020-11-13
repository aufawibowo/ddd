<?php

namespace A7Pro\Chat\Core\Application\Services\GetChatList;

use A7Pro\Chat\Core\Domain\Exceptions\ValidationException;
use A7Pro\Chat\Core\Domain\Repositories\ChatRepository;

class GetChatListService
{
    private ChatRepository $chatRepository;

    public function __construct(
        ChatRepository $chatRepository
    ) {
        $this->chatRepository = $chatRepository;
    }

    public function execute(GetChatListRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $chatList = $this->chatRepository->getChatList($request->userId, $request->isRead, $request->isUnreplied);

        return $chatList;
    }
}
