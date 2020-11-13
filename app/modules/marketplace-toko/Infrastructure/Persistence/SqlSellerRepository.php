<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Order;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Seller;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\SellerRepository;
use PDO;

class SqlSellerRepository implements SellerRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getSellerProfile(string $sellerId): ?array
    {
        $sql = "select 
                    s.user_id, s.name as shop_name, username, email, email_verified_at,
                    u.name as user_fullname, phone, phone_verified_at, profile_pict,
                    gender, place_of_birth, date_of_birth,
                    location, lat, lng, description, working_day,
                    opening_hour, closing_hour, s.created_at
                from
                    sellers s
                inner join
                    users u on u.id = s.user_id
                where
                    user_id = :id
                    and s.deleted_at is null
                    and u.deleted_at is null";
        $param = ['id' => $sellerId];

        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        return $result;
    }

    public function getSellerProfileById(string $sellerId): ?array
    {
        $sql = "select 
                    s.user_id, s.name as shop_name, s.regency,
                    s.location, s.description, s.created_at,
                    u.profile_pict
                from
                    sellers s
                inner join
                    users u on u.id = s.user_id
                where
                    user_id = :id
                    and s.deleted_at is null
                    and u.deleted_at is null";
        $param = ['id' => $sellerId];
        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if(!$result)
            return null;

        $sql = "select
                    count(*) as sold_products_count
                from
                    sellers s
                left join
                    orders o on o.seller_id = s.user_id
                left join
                    order_products op on op.order_id = o.id
                where
                    user_id = :id
                    and s.deleted_at is null
                    and o.deleted_at is null
                    and o.status = :status";
        $param = ['id' => $sellerId, 'status' => Order::STATUS_RECEIVED];
        $result['sold_products_count'] =
            $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param)['sold_products_count'];

        $sql = "select avg(rating) as rating, count(*) as reviews_count
                from
                    orders o
                inner join
                    order_products op on op.order_id = o.id
                inner join
                    products p on op.product_id = p.id
                inner join
                    reviews r on r.product_id = p.id
                where
                    o.seller_id = :id
                    and o.deleted_at is null
                    and o.status = :status";
        $param = ['id' => $sellerId, 'status' => Order::STATUS_RECEIVED];
        $rating = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);
        
        $result = array_merge($result, $rating);

        return $result;
    }

    public function updateSellerProfile(Seller $seller): bool
    {
        $sql = "update
                    sellers
                set
                    gender = :gender,
                    place_of_birth = :place_of_birth,
                    date_of_birth = :date_of_birth,
                    location = :location,
                    lat = :lat,
                    lng = :lng,
                    description = :description,
                    working_day = :working_day,
                    opening_hour = :opening_hour,
                    closing_hour = :closing_hour
                where
                    user_id = :seller_id";
        $params = [
            'seller_id' => $seller->getSellerId(),
            'gender' => $seller->getGender(),
            'place_of_birth' => $seller->getPlaceOfBirth(),
            'date_of_birth' => $seller->getDateOfBirth(),
            'location' => $seller->getLocation(),
            'lat' => $seller->getLatitude(),
            'lng' => $seller->getLongitude(),
            'description' => $seller->getDescription(),
            'working_day' => $seller->getWorkingDays(),
            'opening_hour' => $seller->getOpeningHours(),
            'closing_hour' => $seller->getClosingHours()
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }

    public function getSellerByEmailOrUsername(string $email, string $username): array
    {
        $sql = "select
                    *
                from
                    users u
                join
                    sellers s on s.user_id = u.id
                where
                    email = :email
                    or username = :username";
        $params = ['email' => $email, 'username' => $username];

        return $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);
    }

    public function save(Seller $seller): bool
    {
        $sqlInsertUser = "insert into users
                    (id, name, username, email, phone, profile_pict, password, status)
                values
                    (:id, :name, :username, :email, :phone, :profile_pict, :password, :status)";
        $paramsInsertUser = [
            'id' => $seller->getSellerId(),
            'name' => $seller->getName(),
            'username' => $seller->getUsername(),
            'email' => $seller->getEmail(),
            'phone' => $seller->getPhone(),
            'profile_pict' => $seller->getProfilePict(),
            'password' => $seller->getPassword(),
            'status' => 'active'
        ];

        $sqlInsertSeller = "insert into sellers
                    (user_id, name, regency, postal_code, location, lat, lng, description)
                values
                    (:user_id, :name, :regency, :postal_code, :location, :lat, :lng, :description)";
        $paramsInsertSeller = [
            'user_id' => $seller->getSellerId(),
            'name' => $seller->getShopName(),
            'regency' => $seller->getRegency(),
            'postal_code' => $seller->getPostalCode(),
            'location' => $seller->getLocation(),
            'lat' => $seller->getLatitude(),
            'lng' => $seller->getLongitude(),
            'description' => $seller->getDescription(),
        ];

        $sqlInsertRole = "insert into roles
                    (user_id, role)
                values
                    (:user_id, :role)";
        $paramsInsertRole = [
            'user_id' => $seller->getSellerId(),
            'role' => 'seller',
        ];

        try {
            $this->db->begin();

            $this->db->execute($sqlInsertUser, $paramsInsertUser);

            $this->db->execute($sqlInsertSeller, $paramsInsertSeller);

            $this->db->execute($sqlInsertRole, $paramsInsertRole);

            $this->db->commit();
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }

    public function getHomeData(string $sellerId): array
    {
        $sql = "select count(*) as unread_chat
                from chats
                where is_read = 0 and receiver_id = :seller_id
                    and deleted_at is null";
        $params = ['seller_id' => $sellerId];
        $data['unread_chat'] = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params)['unread_chat'];

        $sql = "select count(*) as order_unconfirmed
                from orders
                where status = :status and seller_id = :seller_id
                    and deleted_at is null";
        $params = ['status' => Order::STATUS_ONORDER, 'seller_id' => $sellerId];
        $data['order_unconfirmed'] = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params)['order_unconfirmed'];

        $sql = "select count(*) as order_unconfirmed
                from orders
                where status = :status and seller_id = :seller_id
                    and deleted_at is null";
        $params = ['status' => Order::STATUS_ONORDER, 'seller_id' => $sellerId];
        $data['order_unconfirmed'] = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params)['order_unconfirmed'];

        // select complained order
        $data['order_complained'] = 0;

        // get income the last 3 months
        $months = $this->getLast3Months();
        foreach ($months as $key => $value) {
            $sql = "select sum(amount) as total_amount from order_products op
                    inner join
                        orders o on o.id = op.order_id
                    where seller_id = :seller_id
                        and o.deleted_at is null
                        and status = :status
                        and o.updated_at >= :first
                        and o.updated_at <= :last";
            $params = [
                'seller_id' => $sellerId,
                'status' => Order::STATUS_RECEIVED,
                'first' => $value['first'],
                'last' => $value['last'],
            ];

            $data['incomings'][$key]['amount'] = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $params)['total_amount'];
            
            $sql = "select
                        p.id, p.name, op.amount
                    from
                        order_products op
                    inner join
                        products p on p.id = op.product_id
                    inner join
                        orders o on o.id = op.order_id
                    where
                        o.seller_id = :seller_id
                        and o.deleted_at is null
                        and o.status = :status
                        and o.updated_at >= :first
                        and o.updated_at <= :last
                    order by o.updated_at desc
                    limit 2";
            
            $data['incomings'][$key]['products'] = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $params);
        }

        return $data;
    }

    private function getLast3Months(): array
    {
        $date = [];
        for ($i = -2; $i <= 0; $i++)
            $date[date('M', strtotime("$i month"))] = [
                'first' => date('Y-m', strtotime("$i month"))."-1 00:00:00",
                'last' => date('Y-m-t 00:00:00', strtotime("$i month"))
            ];

        return $date;
    }
}
