<?php

namespace A7Pro\Account\Core\Domain\Models;

class Dpc
{
    private DpcId $id;
    private string $code;
    private string $name;
    private Dpd $dpd;

    public function __construct(DpcId $id, string $code, string $name, Dpd $dpd)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->dpd = $dpd;
    }

    public function getId(): DpcId
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

    public function getDpd(): Dpd
    {
        return $this->dpd;
    }

    public function equals(Dpc $dpc): bool
    {
        return $this->getId()->id() === $dpc->getId()->id();
    }
}