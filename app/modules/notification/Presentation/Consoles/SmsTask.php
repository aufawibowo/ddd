<?php

namespace A7Pro\Notification\Presentation\Consoles;

use A7Pro\Notification\Core\Application\Services\SendSms\SendSmsRequest;
use A7Pro\Notification\Core\Application\Services\SendSms\SendSmsService;
use Phalcon\Cli\Task;

class SmsTask extends Task
{
    public function sendAction(string $phone, string $message)
    {
        $request = new SendSmsRequest($phone, $message);
        $service = new SendSmsService($this->container['smsService']);

        if ($service->execute($request)) {
            echo "SMS sent." . PHP_EOL;
        } else {
            echo "SMS failed to send." . PHP_EOL;
        }
    }
}
