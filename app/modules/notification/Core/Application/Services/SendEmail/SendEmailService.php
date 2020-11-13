<?php

namespace A7Pro\Notification\Core\Application\Services\SendEmail;

use A7Pro\Notification\Core\Domain\Services\EmailService;

class SendEmailService
{
    private EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function execute(SendEmailRequest $request)
    {
        $this->emailService->send($request->from, $request->fromName, $request->to, $request->subject, $request->body);
    }
}