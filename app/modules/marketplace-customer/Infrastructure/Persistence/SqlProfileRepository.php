<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\Date;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ShippingProfile;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProfileRepository;
use Exception;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;
use PDO;

class SqlProfileRepository implements ProfileRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getProfile(string $customerId)
    {
        $sql = "select
                    u.name, u.email, u.phone
                from 
                    customers
                inner join
                    users u on customers.user_id = u.id
                where
                    customers.user_id = :customer_id
        ";

        $params = ['customer_id' => $customerId];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollback();

            return false;
        }
    }

    public function addAddress(ShippingProfile $address)
    {
        $sql =    "insert into 
                        shipping_profile (id, address, nama_penerima, nomor_hp_penerima, user_id, latitude, longitude, label, created_at, updated_at)
                    values (:id, :new_address, :nama_penerima, :nomor_hp_penerima, :customer_id, :latitude, :longitude, :label, :created_at, :updated_at)";

        $params = [
            'id'                => $address->getShippingProfileId(),
            'new_address'       => $address->getAddress(),
            'nama_penerima'     => $address->getNamaPenerima(),
            'nomor_hp_penerima' => $address->getNomorHpPenerima(),
            'customer_id'       => $address->getCustomerId(),
            'latitude'          => (string)$address->getLatitude(),
            'longitude'         => (string)$address->getLongitude(),
            'label'             => $address->getLabel(),
            'created_at'        => $address->getCreatedAt(),
            'updated_at'        => $address->getUpdatedAt()
        ];

        try {
            $this->db->begin();
            $this->db->execute($sql, $params);
            $this->db->commit();

            return true;
        } catch (Exception $e) {
            $this->db->rollback();

            return var_dump($e->getMessage());
        }
    }

    public function getAddress(string $customerId)
    {
        $sql = "select *
                from shipping_profile
                where user_id = :customer_id";

        $param = [
            'customer_id' => $customerId
        ];

        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        return $results;
    }

    public function getAddressById(string $addressId): ?string
    {
        $sql = "select 
                    address, nama_penerima, nomor_hp_penerima,
                    latitude, longitude, label
                from shipping_profile
                where id = :address_id";

        $param = [
            'address_id' => $addressId
        ];

        $results = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        return json_encode($results);
    }
}
