<?php

namespace A7Pro\Notification\Core\Domain\Services;

interface EmailService
{
    public function send(string $from, string $fromName, string $to, string $subject, string $body);
    public function sendHtml(string $from, string $fromName, string $to, string $subject, string $body, string $altBody);
}
