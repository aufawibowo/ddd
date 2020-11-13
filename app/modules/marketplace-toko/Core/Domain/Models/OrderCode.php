<?php


namespace A7Pro\Marketplace\Toko\Core\Domain\Models;


use phpDocumentor\Reflection\Types\This;

class OrderCode
{
    private string $code;

    /**
     * OrderCode constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->code;
    }

    public static function createFromOrderType(string $orderType)
    {
        $date = (new \DateTime())->format('ymd');
        $randomNumber = rand(100, 999);
        $code = $orderType . $date . $randomNumber;

        return new self($code);
    }
}
