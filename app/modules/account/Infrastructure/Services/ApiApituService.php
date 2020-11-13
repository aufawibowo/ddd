<?php

namespace A7Pro\Account\Infrastructure\Services;

use A7Pro\Account\Core\Domain\Models\ApituMember;
use A7Pro\Account\Core\Domain\Models\Phone;
use A7Pro\Account\Core\Domain\Services\ApituService;
use A7Pro\Traits\ExternalService;
use Phalcon\Config;

class ApiApituService implements ApituService
{
    use ExternalService;

    private $baseUrl;

    public function __construct(Config $config)
    {
        $this->baseUrl = $config->path('services.apitu.baseUrl');
    }

    public function getMemberByMemberId(string $memberId): ?ApituMember
    {
        $response = $this->sendRequest($this->baseUrl . "/api/anggota/{$memberId}", 'GET');

        if (!$response['success'])
            return null;

        $data = $response['data'];

        return new ApituMember(
            $data['noanggota'],
            $data['nama'],
            new Phone($data['tel']),
            $data['alamat'],
            $data['kota'],
            $data['dpc']['kode']
        );
    }
}