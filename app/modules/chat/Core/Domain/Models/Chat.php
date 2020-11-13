<?php

namespace A7Pro\Chat\Core\Domain\Models;

class Chat
{
    private ChatId $id;
    private string $senderId;
    private string $receiverId;
    private string $message;
    private Date $createdAt;
    private int $read;

    public function __construct(
        ChatId $id,
        string $senderId,
        string $receiverId,
        string $message,
        Date $createdAt,
        int $read = 0
    ) {
        $this->id = $id;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->message = $message;
        $this->read = $read;
        $this->createdAt = $createdAt;
        $this->read = $read;
    }

    public function getId(): ChatId
    {
        return $this->id;
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function getReceiverId(): string
    {
        return $this->receiverId;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): Date
    {
        return $this->createdAt;
    }

    public function isRead(): int
    {
        return $this->read;
    }

    public function getMessageStatus(string $userId): string
    {
        $status = 'Unknown';

        switch ($userId) {
            case $this->getReceiverId():
                $status = 'received';
                break;

            case $this->getSenderId():
                $status = 'sent';
                break;
        }

        return $status;
    }

    public function validate()
    {
        $errors = [];

        if (!($this->id && $this->id->isValid()))
            $errors[] = 'cost_category_invalid_id';

        if (empty($this->senderId))
            $errors[] = 'sender_id_cannot_be_empty';

        if (empty($this->receiverId))
            $errors[] = 'sender_id_cannot_be_empty';

        if (empty($this->message))
            $errors[] = 'sender_id_cannot_be_empty';

        return $errors;
    }
}
