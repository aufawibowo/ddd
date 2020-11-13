<?php

namespace A7Pro\Notification\Core\Application\Services\SendSms;

class SendSmsRequest
{
    public ?string $phone;
    public ?string $message;

    public function __construct(?string $phone, ?string $message)
    {
        $this->phone = $phone;
        $this->message = $message;
    }
}
