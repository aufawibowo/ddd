<?php

namespace A7Pro\Chat\Core\Application\Services\SendChat;

use A7Pro\Chat\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Chat\Core\Domain\Exceptions\ValidationException;
use A7Pro\Chat\Core\Domain\Models\Chat;
use A7Pro\Chat\Core\Domain\Models\ChatId;
use A7Pro\Chat\Core\Domain\Models\Date;
use A7Pro\Chat\Core\Domain\Repositories\ChatRepository;

class SendChatService
{
    private ChatRepository $chatRepository;

    public function __construct(
        ChatRepository $chatRepository
    ) {
        $this->chatRepository = $chatRepository;
    }

    public function execute(SendChatRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if(!$this->chatRepository->isReceiverExist($request->userId, $request->receiverId))
            throw new InvalidOperationException('receiver_not_found');

        $chat = new Chat(
            new ChatId(),
            $request->userId,
            $request->receiverId,
            $request->message,
            new Date(new \DateTime())
        );

        // validate created cost category
        $errors = $chat->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist created chat message
        $this->chatRepository->save($chat);
    }
}
