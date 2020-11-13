<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Services;

interface EventPublisher
{
    public function publish($channel, $event, $data = null);
}