<?php

namespace A7Pro\Chat\Infrastructure\Persistence;

use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Chat\Core\Domain\Repositories\ChatRepository;
use A7Pro\Chat\Core\Domain\Models\Chat;
use A7Pro\Chat\Core\Domain\Models\ChatId;
use A7Pro\Chat\Core\Domain\Models\Date;
use PDO;

class SqlChatRepository implements ChatRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getHistoryByUserId(
        string $userId,
        string $receiverId,
        int $page,
        int $limit
    ): array {
        if (!$this->readChat($userId, $receiverId))
            return false;

        $sql = "select 
                    c.id, c.message, c.sender_id, c.receiver_id, c.created_at, c.is_read
                from 
                    chats c
                where 
                    (c.sender_id = :user_id and c.receiver_id = :receiver_id)
                    or (c.sender_id = :receiver_id and c.receiver_id = :user_id)
                order by
                    c.created_at desc";

        $params = [
            'user_id' => $userId,
            'receiver_id' => $receiverId
        ];

        if ($limit && $page)
            $sql = $sql . "
                limit " . ($page - 1) * $limit . ", " . $limit;

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        $data = [];
        if ($results) {
            foreach ($results as $result) {
                $data[] = new Chat(
                    new ChatId($result['id']),
                    $result['sender_id'],
                    $result['receiver_id'],
                    $result['message'],
                    new Date(new \DateTime($result['created_at'])),
                    $result['is_read']
                );
            }
        }

        return $data;
    }

    public function getChatList(string $userId, int $isUnread, int $isUnreplied): array
    {
        $sql = "select distinct 
                    sender_id, receiver_id
                from 
                    chats
                where 
                    (sender_id = :user_id 
                    or receiver_id = :user_id)
                    and deleted_at is null";
        $param = ['user_id' => $userId];
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $sender_receiver_ids = [];
        $data = [];

        foreach ($results as $key => $result) {
            // get message
            $id = $result['sender_id'] == $userId ? $result['receiver_id'] : $result['sender_id'];

            if (in_array($id, $sender_receiver_ids))
                continue;

            $sql = "select
                        c.receiver_id, c.sender_id, c.message, c.created_at, c.is_read
                    from 
                        chats c
                    where 
                        ((c.sender_id = :user_id and c.receiver_id = :receiver_id)
                        or (c.sender_id = :receiver_id and c.receiver_id = :user_id))
                        and deleted_at is null
                    order by
                        created_at desc
                    limit 1";
            $params = [
                'user_id' => $result['sender_id'],
                'receiver_id' => $result['receiver_id']
            ];
            $msg = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);
            $msg["status"] = $result['sender_id'] == $userId ? "sent" : "received";

            $sender_receiver_ids[] = $id;

            if ($isUnreplied && $msg["status"] == "sent")
                continue;

            // get user profile
            $userProfileId = $msg['sender_id'] == $userId ? $msg['receiver_id'] : $msg['sender_id'];

            $sql = "select
                        u.id as user_id, u.name, u.profile_pict
                    from 
                        users u
                    where 
                        u.id = :user_profile_id
                        and deleted_at is null
                    limit 1";
            $param = ['user_profile_id' => $userProfileId];
            $profile = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

            if(!$profile)
                continue;

            // add message created at
            $createdAt = new Date(new \DateTime($msg['created_at']));
            $msg['created_at'] = $createdAt->toIsoDateTimeString();

            // add message unread_count
            $sql = "select 
                        count(*) as unread_count
                    from 
                        chats c
                    where 
                        c.sender_id = :receiver_id
                        and c.receiver_id = :user_id
                        and is_read = 0";
            $unread = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

            if ($isUnread && !$unread['unread_count'])
                continue;

            $msg['unread_count'] = $unread['unread_count'];

            unset($msg["is_read"], $msg['receiver_id'], $msg['sender_id']);
            $msg = array_merge($profile, $msg);

            $data[] = $msg;
        }

        usort($data, function($a, $b){
            return strcmp($a->created_at, $b->created_at);
        });

        return $data;
    }

    public function isReceiverExist(string $senderId, string $receiverId): ?string
    {
        $senderSql = "select
                        role
                    from
                        users u
                    inner join
                        roles r on r.user_id = u.id
                    where u.id = :sender_id";
        $senderParams = [
            'sender_id' => $senderId
        ];

        $role = "";
        if($this->db->fetchOne($senderSql, PDO::FETCH_ASSOC, $senderParams)['role'] == "seller")
            $role = "customer";
        else
            $role = "seller";

        $receiverSql = "select
                            u.id
                        from
                            users u
                        inner join
                            roles r on r.user_id = u.id
                        where u.id = :receiver_id
                            and role = :role";
        $receiverParams = [
            'receiver_id' => $receiverId,
            'role' => $role
        ];

        if(is_null($this->db->fetchOne($receiverSql, PDO::FETCH_ASSOC, $receiverParams)['id']))
            return false;
        
        return true;
    }

    public function save(Chat $chat): bool
    {
        $sql = "insert into chats 
                    (id, sender_id, receiver_id, message, is_read)
                values 
                    (:id, :sender_id, :receiver_id, :message, :is_read)";

        $params = [
            'id' => $chat->getId()->id(),
            'sender_id' => $chat->getSenderId(),
            'receiver_id' => $chat->getReceiverId(),
            'message' => $chat->getMessage(),
            'is_read' => $chat->isRead()
        ];

        if (!$this->readChat($chat->getSenderId(), $chat->getReceiverId()))
            return false;

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    private function readChat($userId, $senderId)
    {
        $sql = "update chats c
                        set is_read = 1
                    where c.sender_id = :sender_id 
                        and c.receiver_id = :user_id
                        and is_read = 0";

        $params = [
            'user_id' => $userId,
            'sender_id' => $senderId
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }
}
