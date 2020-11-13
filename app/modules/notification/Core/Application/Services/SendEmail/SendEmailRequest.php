<?php

namespace A7Pro\Notification\Core\Application\Services\SendEmail;

class SendEmailRequest
{
    public ?string $from;
    public ?string $fromName;
    public ?string $to;
    public ?string $subject;
    public ?string $body;

    public function __construct(?string $from, ?string $fromName, ?string $to, ?string $subject, ?string $body)
    {
        $this->from = $from;
        $this->fromName = $fromName;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }
}