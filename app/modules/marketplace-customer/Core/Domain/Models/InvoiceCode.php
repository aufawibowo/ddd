<?php


namespace A7Pro\Marketplace\Customer\Core\Domain\Models;


use Ramsey\Uuid\Uuid;

class InvoiceCode
{
    private string $id;

    /**
     * ReviewId constructor.
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