<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;


use Ramsey\Uuid\Uuid;

class OrderId
{
    private string $id;

    /**
     * ProductId constructor.
     * @param string $id
     */
    public function __construct(string $id = null)
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
