<?php

namespace A7Pro\Notification\Presentation\Consoles;

use A7Pro\Notification\Core\Application\Services\SendEmail\SendEmailRequest;
use A7Pro\Notification\Core\Application\Services\SendEmail\SendEmailService;
use Phalcon\Cli\Task;

class EmailTask extends Task
{
    public function sendAction(string $from, string $fromName, string $to, string $subject, string $body)
    {
        $request = new SendEmailRequest($from, $fromName, $to, $subject, $body);
        $service = new SendEmailService($this->container['emailService']);

        $service->execute($request);
    }
}