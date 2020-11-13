<?php

namespace A7Pro\Chat\Infrastructure\Persistence;

use A7Pro\Chat\Core\Domain\Models\User;
use A7Pro\Chat\Core\Domain\Models\UserId;
use A7Pro\Chat\Core\Domain\Repositories\UserRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

class SqlUserRepository implements UserRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getUserById(string $userId): User
    {
        $sql = "select 
                    id, name, profile_pict
                from
                    users
                where
                    id = :user_id";

        $param = ['user_id' => $userId];

        $result = $this->db->fetchOne($sql, \PDO::FETCH_ASSOC, $param);

        return new User(
            $result['id'],
            $result['name'],
            $result['profile_pict']
        );
    }
}