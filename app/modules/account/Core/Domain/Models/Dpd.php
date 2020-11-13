<?php

namespace A7Pro\Account\Core\Domain\Models;

class Dpd
{
    private DpdId $id;
    private string $code;
    private string $name;

    public function __construct(DpdId $id, string $code, string $name)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): DpdId
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }
}