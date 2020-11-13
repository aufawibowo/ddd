<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;

use A7Pro\Marketplace\Customer\Core\Domain\Models\ProductPhotosId;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ProductPhotosRepository;
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
}