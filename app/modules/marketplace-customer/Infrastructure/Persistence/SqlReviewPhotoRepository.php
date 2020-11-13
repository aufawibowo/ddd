<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;


use A7Pro\Marketplace\Customer\Core\Domain\Models\Date;
use A7Pro\Marketplace\Customer\Core\Domain\Models\ReviewPhotosId;
use A7Pro\Marketplace\Customer\Core\Domain\Repositories\ReviewPhotoRepository;
use Phalcon\Db\Adapter\Pdo\AbstractPdo;

class SqlReviewPhotoRepository extends SqlBaseRepository implements ReviewPhotoRepository
{
    private AbstractPdo $db;

    public function __construct(AbstractPdo $db)
    {
        $this->db = $db;
    }

    public function getPhotoById()
    {
        $sql = "select
                    id, product_id, photo_url, created_at
                from
                    product_photos
                where
                    id = :id
                    and deleted_at is null
                limit 1";
        $param = ['id' => $photoId]; //TODO : Error:(31, 27) Undefined variable '$photoId'
        $result = $this->db->fetchOne($sql, PDO::FETCH_ASSOC, $param);

        if (!$result) return null;

        return $result;
    }

    public function save(array $photos, string $reviewId)
    {
        $sql = "insert into review_photos
                    (id, review_id, photo_url)
                values 
                    (:id, :review_id, :photo_url)";

        $id = new ReviewPhotosId();

        $param = [
            'id'        => $id->id(),
            'review_id' => $reviewId,
            'photo_url' => json_encode($photos)
        ];

        try {

            $this->db->begin();
            $this->db->execute($sql, $param);
            $this->db->commit();

        }
        catch (\Throwable $th) {

            var_dump($th->getMessage());
            $this->db->rollback();
            return false;
        }
        return true;
    }

    public function delete()
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
            'id' => $photoId, //TODO: Error:(81, 21) Undefined variable '$photoId'
            'deleted_at' => $deletedAt->toDateTimeString()
        ];

        try {
            $this->db->begin();

            $this->db->execute($sql, $param);

            $this->db->commit();
        } catch (\Throwable $th) {
            var_dump($th->getMessage());
            $this->db->rollback();

            return false;
        }

        return true;
    }
}
