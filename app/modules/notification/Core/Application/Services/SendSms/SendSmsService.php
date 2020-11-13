<?php

namespace A7Pro\Notification\Core\Application\Services\SendSms;

use A7Pro\Notification\Core\Domain\Services\SmsService;

class SendSmsService
{
    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function execute(SendSmsRequest $request)
    {
        return $this->smsService->send($request->phone, $request->message);
    }
}
