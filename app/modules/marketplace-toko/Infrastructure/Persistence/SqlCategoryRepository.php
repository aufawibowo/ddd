<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Persistence;

use Phalcon\Db\Adapter\Pdo\AbstractPdo;

use A7Pro\Marketplace\Toko\Core\Domain\Repositories\CategoryRepository;
use PDO;

class SqlCategoryRepository implements CategoryRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getCategoriesList(): array
    {
        $sql = "select id, name from product_categories";
        $results = $this->db->fetchAll($sql, PDO::FETCH_ASSOC);

        return $results;
    }
}
