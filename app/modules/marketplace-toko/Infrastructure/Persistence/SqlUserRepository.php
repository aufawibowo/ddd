<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Email;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Phone;
use A7Pro\Marketplace\Toko\Core\Domain\Models\User;
use A7Pro\Marketplace\Toko\Core\Domain\Repositories\UserRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use PDO;

class SqlUserRepository implements UserRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    private function createUserFromQueryResult(array $result): User
    {
        $roles = explode(',', $result['roles']);

        $emailVerifiedAt = $result['email_verified_at'] ? new Date(new \DateTime($result['email_verified_at'])) : null;
        $email = $result['email'] ? new Email($result['email'], $emailVerifiedAt) : null;

        $phoneVerifiedAt = $result['phone_verified_at'] ? new Date(new \DateTime($result['phone_verified_at'])) : null;
        $phone = $result['phone'] ? new Phone($result['phone'], $phoneVerifiedAt) : null;

        return new User(
            $result['id'],
            $result['name'],
            $result['username'],
            $email,
            $phone,
            $result['profile_pict'],
            $result['password'],
            $result['status'],
            $roles
        );
    }

    public function getByPhone(string $phone): ?User
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth, t.*,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join sellers t on t.user_id = u.id
                    inner join roles r on r.user_id = u.id
                where u.phone = :phone
                    and u.deleted_at is null
                    and role = 'seller'
                group by u.id";

        $params = ['phone' => $phone];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($result) {
            return $this->createUserFromQueryResult($result);
        }

        return null;
    }

    public function getByEmail(string $email): ?User
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth, t.*,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join sellers t on t.user_id = u.id
                    inner join roles r on r.user_id = u.id
                where u.email = :email
                    and u.deleted_at is null
                    and role = 'seller'
                group by u.id";

        $params = ['email' => $email];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($result) {
            return $this->createUserFromQueryResult($result);
        }

        return null;
    }

    public function getByUsername(string $username): ?User
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth, t.*,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join sellers t on t.user_id = u.id
                    inner join roles r on r.user_id = u.id
                where u.username = :username
                    and u.deleted_at is null
                    and role = 'seller'
                group by u.id";

        $params = ['username' => $username];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($result) {
            return $this->createUserFromQueryResult($result);
        }

        return null;
    }

    public function isPhoneExists(string $phone): bool
    {
        $sql = "select exists(select * from users where phone = :phone) as registered";

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, ['phone' => $phone]);
        
        return (bool) $result['registered'];
    }
}