<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\StorefrontRepository;
use A7Pro\Marketplace\Toko\Core\Domain\Models\Storefront;
use A7Pro\Marketplace\Toko\Core\Domain\Models\StorefrontId;
use PDO;

class SqlStorefrontRepository implements StorefrontRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getStorefrontById(string $storefrontId): ?Storefront
    {
        $sql = "select
                    id, name, seller_id
                from
                    storefronts
                where
                    id = :id
                    and deleted_at is null";
        $param = ['id' => $storefrontId];
        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if ($result)
            return new Storefront(
                new StorefrontId($result['id']),
                $result['seller_id'],
                $result['name']
            );

        return null;
    }

    public function getStorefronts(string $sellerId): array
    {
        $sql = "select
                    id, name
                from
                    storefronts
                where
                    seller_id = :seller_id
                    and deleted_at is null";
        $param = ['seller_id' => $sellerId];
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC, $param);

        return $results;
    }

    public function update(Storefront $storefront): bool
    {
        $sql = "update
                    storefronts 
                set
                    name = :name
                where 
                    id = :id";

        $params = [
            'name' => $storefront->getName(),
            'id' => $storefront->getId()->id()
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

    public function save(Storefront $storefront): bool
    {
        $sql = "insert into storefronts 
                    (id, name, seller_id)
                values 
                    (:id, :name, :seller_id)";

        $params = [
            'id' => $storefront->getId()->id(),
            'name' => $storefront->getName(),
            'seller_id' => $storefront->getSellerId()
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

    public function delete(Storefront $storefront): bool
    {
        $sqlUpdateProducts =
            "update products
                set id = NULL
            where
                storefront_id = :storefront_id";
        $paramUpdateProducts =
            ['storefront_id' => $storefront->getId()->id()];

        $sql = "update
                    storefronts
                set deleted_at = :deleted_at
                    where id = :id";

        $deletedAt = new Date(new \DateTime());
        $params = [
            'id' => $storefront->getId()->id(),
            'deleted_at' => $deletedAt->toDateTimeString()
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $params);

            $this->db->execute($sqlUpdateProducts, $paramUpdateProducts);

            $this->db->commit();

            return true;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->db->rollback();

            return false;
        }
    }
}
