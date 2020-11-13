<?php

namespace A7Pro\Chat\Presentation\Controllers;

use A7Pro\Chat\Core\Application\Services\GetChatHistory\GetChatHistoryRequest;
use A7Pro\Chat\Core\Application\Services\GetChatHistory\GetChatHistoryService;
use A7Pro\Chat\Core\Application\Services\GetChatList\GetChatListRequest;
use A7Pro\Chat\Core\Application\Services\GetChatList\GetChatListService;
use A7Pro\Chat\Core\Application\Services\SendChat\SendChatRequest;
use A7Pro\Chat\Core\Application\Services\SendChat\SendChatService;

class ChatController extends BaseController
{
    public function GetChatListAction()
    {
        $userId = $this->getAuthUserId();
        $isUnread = (int) $this->request->get('is_unread') ?: 0;
        $isUnreplied = (int) $this->request->get('is_unreplied') ?: 0;

        $request = new GetChatListRequest($userId, $isUnread, $isUnreplied);
        $service = new GetChatListService($this->di->get('chatRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function sendChatAction()
    {
        $receiverId = $this->dispatcher->getParam('receiver_id');
        $userId = $this->getAuthUserId();
        $message = $this->request->get('message');

        $request = new SendChatRequest($userId, $receiverId, $message);
        $service = new SendChatService($this->di->get('chatRepository'));

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function getChatHistoryAction()
    {

        $receiverId = $this->dispatcher->getParam('receiver_id');
        $userId = $this->getAuthUserId();
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');

        $request = new GetChatHistoryRequest($userId, $receiverId, $page, $limit);

        $service = new GetChatHistoryService(
            $this->di->get('chatRepository'),
            $this->di->get('userRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
