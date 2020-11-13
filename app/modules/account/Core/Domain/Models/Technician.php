<?php

namespace A7Pro\Account\Core\Domain\Models;

class Technician
{
    private string $apituId;
    private Dpc $dpc;
    private Address $address;
    private bool $receiveOrder;

    public function __construct(string $apituId, Dpc $dpc, Address $address, bool $receiveOrder)
    {
        $this->apituId = $apituId;
        $this->dpc = $dpc;
        $this->address = $address;
        $this->receiveOrder = $receiveOrder;
    }

    public function getApituId(): string
    {
        return $this->apituId;
    }

    public function getDpc(): Dpc
    {
        return $this->dpc;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function isReceiveOrder(): bool
    {
        return $this->receiveOrder;
    }
}
