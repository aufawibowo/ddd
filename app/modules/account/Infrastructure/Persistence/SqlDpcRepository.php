<?php

namespace A7Pro\Account\Infrastructure\Persistence;

use A7Pro\Account\Core\Domain\Models\Dpc;
use A7Pro\Account\Core\Domain\Models\DpcId;
use A7Pro\Account\Core\Domain\Models\Dpd;
use A7Pro\Account\Core\Domain\Models\DpdId;
use A7Pro\Account\Core\Domain\Repositories\DpcRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

class SqlDpcRepository implements DpcRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getByCode(string $code): ?Dpc
    {
        $sql = "select dpc.id, dpc.code, dpc.name,
                    dpd.id as dpd_id, dpd.code as dpd_code, dpd.name as dpd_name
                from dpc
                    inner join dpd on dpd.id = dpc.dpd_id
                where dpc.code = :code
                    and dpc.deleted_at is null";

        $params = ['code' => $code];

        $result = $this->db->fetchOne($sql, \PDO::FETCH_ASSOC, $params);

        if ($result) {
            return new Dpc(
                new DpcId($result['id']),
                $result['code'],
                $result['name'],
                new Dpd(
                    new DpdId($result['dpd_id']),
                    $result['dpd_code'],
                    $result['dpd_name']
                )
            );
        }

        return null;
    }
}