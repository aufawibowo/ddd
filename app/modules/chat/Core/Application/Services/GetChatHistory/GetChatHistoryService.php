<?php

namespace A7Pro\Chat\Core\Application\Services\GetChatHistory;

use A7Pro\Chat\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Chat\Core\Domain\Repositories\UserRepository;
use A7Pro\Chat\Core\Domain\Exceptions\ValidationException;
use A7Pro\Chat\Core\Domain\Repositories\ChatRepository;

class GetChatHistoryService
{
    private ChatRepository $chatRepository;
    private UserRepository $userRepository;

    public function __construct(
        ChatRepository $chatRepository,
        UserRepository $userRepository
    ) {
        $this->chatRepository = $chatRepository;
        $this->userRepository = $userRepository;
    }

    public function execute(GetChatHistoryRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        if(!$this->chatRepository->isReceiverExist($request->userId, $request->receiverId))
            throw new InvalidOperationException('receiver_not_found');

        $history = $this->chatRepository->getHistoryByUserId(
            $request->userId,
            $request->receiverId,
            $request->page,
            $request->limit
        );

        $receiver = $this->userRepository->getUserById($request->receiverId);

        return [
            'receiver' => $receiver,
            'history' => (new GetChatHistoryDto($history, $request->userId))->data
        ];
    }
}
