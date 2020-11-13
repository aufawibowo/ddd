<?php

namespace A7Pro\Wallet\Core\Domain\Models;

class TransactionCode
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function code()
    {
        return $this->code;
    }

    public static function createFromTransactionType(string $type)
    {
        $date = (new \DateTime())->format('ymd');
        $random_number = rand(100, 999);
        $code = $type . $date . $random_number;

        return new self($code);
    }
}