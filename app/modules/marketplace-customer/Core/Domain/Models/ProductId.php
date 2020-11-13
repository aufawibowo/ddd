<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;

use Ramsey\Uuid\Uuid;

class ProductId
{
    private string $id;

    /**
     * ProductId constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }


}