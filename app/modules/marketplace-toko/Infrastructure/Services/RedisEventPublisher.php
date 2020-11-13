<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;


use A7Pro\Marketplace\Toko\Core\Domain\Services\EventPublisher;
use Predis\Client;


class RedisEventPublisher implements EventPublisher
{
    private Client $redis;

    /**
     * RedisEventPublisher constructor.
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }


    public function publish($channel, $event, $data = null)
    {
        $message = [
            'event' => $event,
            'data' => $data,
        ];

        $this->redis->publish($channel, json_encode($message));
    }
}