<?php

namespace A7Pro\Marketplace\Toko\Infrastructure\Services;

use A7Pro\Marketplace\Toko\Core\Domain\Services\SmsService;
use A7Pro\Traits\ExternalService;
use Phalcon\Config;

class GoSmsService implements SmsService
{
    use ExternalService;

    private $baseUrl;
    private $username;
    private $password;

    public function __construct(Config $config)
    {
        $this->baseUrl = $config->path('services.sms.endpoint');
        $this->username = $config->path('services.sms.username');
        $this->password = $config->path('services.sms.password');
    }

    public function send(string $phone, string $message): bool
    {
        $auth = md5($this->username . $this->password . $phone);
        $body = [
            'username' => $this->username,
            'mobile' => $phone,
            'message' => $message,
            'auth' => $auth,
            'type' => 0
        ];

        $query = $this->generateUrlParams($body);

        $response = $this->sendRequest($this->baseUrl . '/api/sendSMS.php' . $query, 'GET');

        if ($response != 1701)
            return false;

        return true;
    }
}