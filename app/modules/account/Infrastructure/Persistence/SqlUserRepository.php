<?php

namespace A7Pro\Account\Infrastructure\Persistence;

use A7Pro\Account\Core\Domain\Models\Address;
use A7Pro\Account\Core\Domain\Models\Coordinate;
use A7Pro\Account\Core\Domain\Models\Customer;
use A7Pro\Account\Core\Domain\Models\Date;
use A7Pro\Account\Core\Domain\Models\Dpc;
use A7Pro\Account\Core\Domain\Models\DpcId;
use A7Pro\Account\Core\Domain\Models\Dpd;
use A7Pro\Account\Core\Domain\Models\DpdId;
use A7Pro\Account\Core\Domain\Models\Email;
use A7Pro\Account\Core\Domain\Models\Phone;
use A7Pro\Account\Core\Domain\Models\Technician;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Models\UserId;
use A7Pro\Account\Core\Domain\Models\ZipCode;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use Phalcon\Db\Column;
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

        $customerAttributes = null;
        if (in_array(User::ROLE_CUSTOMER, $roles)) {
            $dateOfBirth = $result['date_of_birth'] ? new Date(new \DateTime($result['date_of_birth'])) : null;
            $customerAttributes = new Customer($result['gender'], $dateOfBirth);
        }

        $technicianAttributes = null;
        if (in_array(User::ROLE_TECHNICIAN, $roles) && $result['apitu_id']) {
            $dpd = new Dpd(
                new DpdId($result['dpd_id']),
                $result['dpd_code'],
                $result['dpd_name']
            );

            $dpc = new Dpc(
                new DpcId($result['dpc_id']),
                $result['dpc_code'],
                $result['dpc_name'],
                $dpd
            );

            $address = new Address(
                $result['address'],
                $result['area'],
                $result['city'],
                new ZipCode($result['zip_code']),
                new Coordinate($result['latitude'], $result['longitude'])
            );

            $technicianAttributes = new Technician(
                $result['apitu_id'],
                $dpc,
                $address,
                $result['receive_order']
            );
        }

        return new User(
            new UserId($result['id']),
            $result['name'],
            $result['username'],
            $email,
            $phone,
            $result['profile_pict'],
            $result['password'],
            $result['status'],
            $roles,
            $customerAttributes,
            $technicianAttributes
        );
    }

    public function getByPhone(string $phone): ?User
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join technicians t on t.user_id = u.id
                    left join dpc on dpc.id = t.dpc_id
                    left join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where u.phone = :phone
                    and u.deleted_at is null
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
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join technicians t on t.user_id = u.id
                    left join dpc on dpc.id = t.dpc_id
                    left join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where u.email = :email
                    and u.deleted_at is null
                group by u.id";

        $params = ['email' => $email];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($result) {
            return $this->createUserFromQueryResult($result);
        }

        return null;
    }

    public function getById(string $id): ?User
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join technicians t on t.user_id = u.id
                    left join dpc on dpc.id = t.dpc_id
                    left join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where u.id = :id
                    and u.deleted_at is null
                group by u.id";

        $params = ['id' => $id];

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
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    left join technicians t on t.user_id = u.id
                    left join dpc on dpc.id = t.dpc_id
                    left join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where u.username = :username
                    and u.deleted_at is null
                group by u.id";

        $params = ['username' => $username];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params);

        if ($result) {
            return $this->createUserFromQueryResult($result);
        }

        return null;
    }

    public function getUnverifiedTechnicianInDpc(DpcId $dpcId): array
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    inner join technicians t on t.user_id = u.id
                    inner join dpc on dpc.id = t.dpc_id
                    inner join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where u.status = 'inactive'
                    and r.role = 'technician'
                    and dpc.id = :dpc_id
                    and u.deleted_at is null
                group by u.id";

        $params = ['dpc_id' => $dpcId->id()];

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);

        $data = [];

        if ($results) {
            foreach ($results as $result) {
                $data[] = $this->createUserFromQueryResult($result);
            }
        }

        return $data;
    }

    public function getHeadOfDpcList(): array
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    inner join technicians t on t.user_id = u.id
                    inner join dpc on dpc.id = t.dpc_id
                    inner join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where r.role = 'head_of_dpc'
                    and u.deleted_at is null
                group by u.id";

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $data = [];

        if ($results) {
            foreach ($results as $result) {
                $data[] = $this->createUserFromQueryResult($result);
            }
        }

        return $data;
    }

    public function getHeadOfDppList(): array
    {
        $sql = "select u.id, u.name, u.username, u.email, u.email_verified_at, u.phone, u.phone_verified_at,
                    u.profile_pict, u.password, u.status,
                    c.gender, c.date_of_birth,
                    t.apitu_id, t.address, t.area, t.city, t.zip_code, t.latitude, t.longitude, t.receive_order,
                    dpc.id as dpc_id, dpc.code as dpc_code, dpc.name as dpc_name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name,
                    group_concat(distinct r.role) as roles
                from users u
                    left join customers c on c.user_id = u.id
                    inner join technicians t on t.user_id = u.id
                    inner join dpc on dpc.id = t.dpc_id
                    inner join dpd on dpd.id = dpc.dpd_id
                    inner join roles r on r.user_id = u.id
                where r.role = 'head_of_dpp'
                    and u.deleted_at is null
                group by u.id";

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $data = [];

        if ($results) {
            foreach ($results as $result) {
                $data[] = $this->createUserFromQueryResult($result);
            }
        }

        return $data;
    }

    public function isPhoneExists(string $phone): bool
    {
        $sql = "select exists(select * from users where phone = :phone) as registered";

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, ['phone' => $phone]);

        return (bool) $result['registered'];
    }

    public function isApituMemberIdExists(string $apituMemberId): bool
    {
        $sql = "select exists (select * from technicians where apitu_id = :apitu_id) as registered";

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, ['apitu_id' => $apituMemberId]);

        return (bool) $result['registered'];
    }

    public function add(User $user): bool
    {
        $saveUserSql = "insert into users
                            (id, name, username, email, email_verified_at, phone, phone_verified_at,
                            profile_pict, password, status)
                        values 
                            (:id, :name, :username, :email, :email_verified_at, :phone, :phone_verified_at, 
                            :profile_pict, :password, :status)";

        $saveUserParams = [
            'id' => $user->getId()->id(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail() ? $user->getEmail()->email() : null,
            'email_verified_at' => $user->getEmail() && $user->getEmail()->getVerifiedAt() ?
                $user->getEmail()->getVerifiedAt()->toIsoDateTimeString() : null,
            'phone' => $user->getPhone() ? $user->getPhone()->phone() : null,
            'phone_verified_at' => $user->getPhone() && $user->getPhone()->getVerifiedAt() ?
                $user->getPhone()->getVerifiedAt()->toIsoDateTimeString() : null,
            'profile_pict' => $user->getProfilePict(),
            'password' => $user->getPassword(),
            'status' => $user->getStatus()
        ];

        $saveRoleSql = "insert into roles (user_id, role) values";
        $saveRoleParams = ['user_id' => $user->getId()->id()];

        foreach ($user->getRoles() as $key => $role) {
            if ($key != 0) $saveRoleSql .= ",";
            $saveRoleSql .= " (:user_id, :role".$key.")";
            $saveRoleParams['role'.$key] = $role;
        }

        try {
            $this->db->begin();

            $this->db->execute($saveUserSql, $saveUserParams);

            $this->db->execute($saveRoleSql, $saveRoleParams);

            $customerAttributes = $user->getCustomerAttributes();
            if ($customerAttributes) {
                $saveCustomerSql = "
                    insert into customers
                        (user_id, gender, date_of_birth)
                    values
                        (:user_id, :gender, :date_of_birth)
                ";

                $saveCustomerParams = [
                    'user_id' => $user->getId()->id(),
                    'gender' => $customerAttributes->getGender(),
                    'date_of_birth' => $customerAttributes->getDateOfBirth() ?
                        $customerAttributes->getDateOfBirth()->toIsoDateTimeString() : null
                ];

                $this->db->execute($saveCustomerSql, $saveCustomerParams);
            }

            $technicianAttributes = $user->getTechnicianAttributes();
            if ($technicianAttributes) {
                $saveTechnicianSql = "
                    insert into technicians
                        (user_id, apitu_id, dpc_id, address, area, city, zip_code, latitude,
                        longitude, receive_order)
                    values
                        (:user_id, :apitu_id, :dpc_id, :address, :area, :city, :zip_code, :latitude, 
                        :longitude, :receive_order)
                ";

                $saveTechnicianParams = [
                    'user_id' => $user->getId()->id(),
                    'apitu_id' => $technicianAttributes->getApituId(),
                    'dpc_id' => $technicianAttributes->getDpc()->getId()->id(),
                    'address' => $technicianAttributes->getAddress()->getAddress(),
                    'area' => $technicianAttributes->getAddress()->getArea(),
                    'city' => $technicianAttributes->getAddress()->getCity(),
                    'zip_code' => $technicianAttributes->getAddress()->getZipCode()->zipCode(),
                    'latitude' => $technicianAttributes->getAddress()->getCoordinate()->getLatitude(),
                    'longitude' => $technicianAttributes->getAddress()->getCoordinate()->getLongitude(),
                    'receive_order' => $technicianAttributes->isReceiveOrder()
                ];

                $types = [
                    'user_id' => Column::BIND_PARAM_STR,
                    'apitu_id' => Column::BIND_PARAM_STR,
                    'dpc_id' => Column::BIND_PARAM_STR,
                    'address' => Column::BIND_PARAM_STR,
                    'area' => Column::BIND_PARAM_STR,
                    'city' => Column::BIND_PARAM_STR,
                    'zip_code' => Column::BIND_PARAM_STR,
                    'latitude' => Column::BIND_PARAM_DECIMAL,
                    'longitude' => Column::BIND_PARAM_DECIMAL,
                    'receive_order' => Column::BIND_PARAM_BOOL,
                ];

                $this->db->execute($saveTechnicianSql, $saveTechnicianParams, $types);
            }

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }

    public function update(User $user): bool
    {
        $updateUserSql = "
            update users set name = :name, username = :username, email = :email, email_verified_at = :email_verified_at,
                phone = :phone, phone_verified_at = :phone_verified_at, profile_pict = :profile_pict, 
                password = :password, status = :status
            where id = :id
        ";

        $updateUserParams = [
            'id' => $user->getId()->id(),
            'name' => $user->getName(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail() ? $user->getEmail()->email() : null,
            'email_verified_at' => $user->getEmail() && $user->getEmail()->getVerifiedAt() ?
                $user->getEmail()->getVerifiedAt()->toIsoDateTimeString() : null,
            'phone' => $user->getPhone() ? $user->getPhone()->phone() : null,
            'phone_verified_at' => $user->getPhone() && $user->getPhone()->getVerifiedAt() ?
                $user->getPhone()->getVerifiedAt()->toIsoDateTimeString() : null,
            'profile_pict' => $user->getProfilePict(),
            'password' => $user->getPassword(),
            'status' => $user->getStatus()
        ];

        $saveRoleSql = "insert ignore into roles (user_id, role) values";
        $saveRoleParams = ['user_id' => $user->getId()->id()];

        foreach ($user->getRoles() as $key => $role) {
            if ($key != 0) $saveRoleSql .= ",";
            $saveRoleSql .= " (:user_id, :role".$key.")";
            $saveRoleParams['role'.$key] = $role;
        }

        try {
            $this->db->begin();

            $this->db->execute($updateUserSql, $updateUserParams);

            $this->db->execute($saveRoleSql, $saveRoleParams);

            $customerAttributes = $user->getCustomerAttributes();
            if ($customerAttributes) {
                $updateCustomerSql = "
                    insert into customers
                        (user_id, gender, date_of_birth)
                    values
                        (:user_id, :gender, :date_of_birth)
                    on duplicate key update gender = :gender, date_of_birth = :date_of_birth
                ";

                $updateCustomerParams = [
                    'user_id' => $user->getId()->id(),
                    'gender' => $customerAttributes->getGender(),
                    'date_of_birth' => $customerAttributes->getDateOfBirth() ?
                        $customerAttributes->getDateOfBirth()->toIsoDateTimeString() : null
                ];

                $this->db->execute($updateCustomerSql, $updateCustomerParams);
            }

            $technicianAttributes = $user->getTechnicianAttributes();
            if ($technicianAttributes) {
                $updateTechnicianSql = "
                    insert into technicians
                        (user_id, apitu_id, dpc_id, address, area, city, zip_code, latitude,
                        longitude, receive_order)
                    values
                        (:user_id, :apitu_id, :dpc_id, :address, :area, :city, :zip_code, :latitude, 
                        :longitude, :receive_order)
                    on duplicate key update apitu_id = :apitu_id, dpc_id = :dpc_id, address = :address, 
                        area = :area, city = :city, zip_code = :zip_code, latitude = :latitude,
                        longitude = :longitude, receive_order = :receive_order
                ";

                $updateTechnicianParams = [
                    'user_id' => $user->getId()->id(),
                    'apitu_id' => $technicianAttributes->getApituId(),
                    'dpc_id' => $technicianAttributes->getDpc()->getId()->id(),
                    'address' => $technicianAttributes->getAddress()->getAddress(),
                    'area' => $technicianAttributes->getAddress()->getArea(),
                    'city' => $technicianAttributes->getAddress()->getCity(),
                    'zip_code' => $technicianAttributes->getAddress()->getZipCode()->zipCode(),
                    'latitude' => $technicianAttributes->getAddress()->getCoordinate()->getLatitude(),
                    'longitude' => $technicianAttributes->getAddress()->getCoordinate()->getLongitude(),
                    'receive_order' => $technicianAttributes->isReceiveOrder()
                ];

                $types = [
                    'user_id' => Column::BIND_PARAM_STR,
                    'apitu_id' => Column::BIND_PARAM_STR,
                    'dpc_id' => Column::BIND_PARAM_STR,
                    'address' => Column::BIND_PARAM_STR,
                    'area' => Column::BIND_PARAM_STR,
                    'city' => Column::BIND_PARAM_STR,
                    'zip_code' => Column::BIND_PARAM_STR,
                    'latitude' => Column::BIND_PARAM_DECIMAL,
                    'longitude' => Column::BIND_PARAM_DECIMAL,
                    'receive_order' => Column::BIND_PARAM_BOOL,
                ];

                $this->db->execute($updateTechnicianSql, $updateTechnicianParams, $types);
            }

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }
}