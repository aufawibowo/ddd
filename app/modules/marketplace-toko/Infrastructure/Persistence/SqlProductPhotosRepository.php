<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use A7Pro\Marketplace\Toko\Core\Domain\Models\Date;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\ProductPhotosRepository;
use PDO;

class SqlProductPhotosRepository implements ProductPhotosRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getPhotoById(string $photoId): ?array
    {
        $sql = "select
                    id, product_id, photo_url, created_at
                from
                    product_photos
                where
                    id = :id
                    and deleted_at is null
                limit 1";
        $param = ['id' => $photoId];
        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if (!$result) return null;

        return $result;
    }

    public function save(array $filenames, string $productId): bool
    {
        $sql = "insert into product_photos
                    (id, product_id, photo_url)
                values ";

        foreach ($filenames as $key => $value) {
            $photoId = explode(".", $filenames[0])[0];
            $sql = $sql . "(" . "'" . $photoId . "', :product_id, '" . $value . "')";

            if (isset($filenames[$key + 1])) $sql = $sql . ",";
        }

        $param = ['product_id' => $productId];

        try {
            $this->db->begin();

            $this->db->execute($sql, $param);

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());

            $this->db->rollback();

            return false;
        }

        return true;
    }

    public function delete(string $photoId): bool
    {
        $sql = "update
                    product_photos
                set
                    deleted_at = :deleted_at
                where
                    deleted_at is null
                    and id = :id";
        $deletedAt = new Date(new \DateTime());
        $param = [
            'id' => $photoId,
            'deleted_at' => $deletedAt->toDateTimeString()
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $param);

            $this->db->commit();
        } catch (\Exception $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }
}
