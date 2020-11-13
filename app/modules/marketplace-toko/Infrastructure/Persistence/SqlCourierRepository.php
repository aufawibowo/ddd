<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Courier;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\CourierRepository;
use PDO;

class SqlCourierRepository implements CourierRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getCouriersList(string $sellerId): array
    {
        $sql = "select id, name, code, type from couriers";
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $couriers = [];
        foreach ($results as $key => $value) {
            $sql = "select
                        1 as is_selected
                    from 
                        seller_courier_pivot scp
                    where 
                        scp.deleted_at is null
                        and seller_id = :seller_id
                        and courier_id = :courier_id
                    limit 1";
            $param = ['seller_id' => $sellerId, 'courier_id' => $value['id']];
            $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

            if (isset($result['is_selected']))
                $results[$key]['is_selected'] = 1;
            else
                $results[$key]['is_selected'] = 0;

            unset($results[$key]['type']);
            switch ($value['type']) {
                case Courier::NEXT_DAY:
                    $couriers['next_day'][] = $results[$key];
                    break;

                case Courier::REGULAR:
                    $couriers['regular'][] = $results[$key];
                    break;

                case Courier::CARGO:
                    $couriers['cargo'][] = $results[$key];
                    break;
            }
        }

        return $couriers;
    }

    public function getCouriersOnCustomerCheckout(string $sellerId): array
    {
        $sql = "select id, name, code, type from couriers";
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        $couriers = [];
        foreach ($results as $key => $value) {
            unset($results[$key]['type']);
            switch ($value['type']) {
                case Courier::NEXT_DAY:
                    $results[$key]['estimation_day'] = '1';
                    $couriers['next_day'][] = $results[$key];
                    break;

                case Courier::REGULAR:
                    $results[$key]['estimation_day'] = '2-3';
                    $couriers['regular'][] = $results[$key];
                    break;

                case Courier::CARGO:
                    $results[$key]['estimation_day'] = '30';
                    $couriers['cargo'][] = $results[$key];
                    break;
            }
        }

        return $couriers;
    }

    public function updateSellerCouriers(array $couriers, string $sellerId): bool
    {
        $sql = "select
                    id, courier_id
                from
                    seller_courier_pivot
                where
                    deleted_at is null
                    and seller_id = :seller_id
                    and courier_id in " . $this->whereInBuilder($couriers);
        $param = ['seller_id' => $sellerId];
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        $delPivot = [];
        $newPivot = [];
        foreach ($couriers as $value) {
            $count = count($delPivot);

            foreach ($results as $key2 => $value2)
                if ($value2['courier_id'] == $value) {
                    $delPivot[] = $value2['id'];
                    break;
                }

            if (count($delPivot) == $count) $newPivot[] = $value;
        }

        $sqlDelPivot = "update
                            seller_courier_pivot
                        set
                            deleted_at = :deleted_at
                        where 
                            deleted_at is null
                            and id in " . $this->whereInBuilder($delPivot);

        $deletedAt = new Date(new \DateTime());
        $paramDelPivot = ['deleted_at' => $deletedAt->toDateTimeString()];

        $sqlCreatePivot = "insert into seller_courier_pivot
                                (seller_id, courier_id)
                            values ";
        foreach ($newPivot as $key => $value) {
            $sqlCreatePivot .= "(:seller_id, '" . $value . "')";

            if (isset($newPivot[$key + 1])) $sqlCreatePivot .= ",";
        }
        $paramCreatePivot = ['seller_id' => $sellerId];

        try {
            $this->db->begin();

            $this->db->execute($sqlDelPivot, $paramDelPivot);

            if (count($newPivot))
                $this->db->execute($sqlCreatePivot, $paramCreatePivot);

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }

    private function whereInBuilder($productsId)
    {
        if (!isset($productsId[0])) return "('')";

        $sqlWhereIdProductIn = "(";
        foreach ($productsId as $key => $value) {
            $sqlWhereIdProductIn .= "'" . $value . "'";

            if (isset($productsId[$key + 1]))
                $sqlWhereIdProductIn .= ",";
            else
                $sqlWhereIdProductIn .= ")";
        }

        return $sqlWhereIdProductIn;
    }
}
