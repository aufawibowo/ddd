<?php

namespace A7Pro\Notification\Infrastructure\Services;

use A7Pro\Notification\Core\Domain\Services\EmailService;
use Phalcon\Config;
use PHPMailer\PHPMailer\PHPMailer;

class PHPMailerEmailService implements EmailService
{
    private $host;
    private $username;
    private $port;
    private $password;
    private $encryption;

    public function __construct(Config $config)
    {
        $this->host = $config->path('mail.host');
        $this->username = $config->path('mail.username');
        $this->port = $config->path('mail.port');
        $this->password = $config->path('mail.password');
        $this->encryption = $config->path('mail.encryption');
    }


    public function send(string $from, string $fromName, string $to, string $subject, string $body)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = $this->encryption;
            $mail->Port = $this->port;

            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);

            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo 'Message has been sent' . PHP_EOL;
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}" . PHP_EOL;
        }
    }

    public function sendHtml(string $from, string $fromName, string $to, string $subject, string $body, string $altBody): bool
    {
        // TODO: Implement sendHtml() method.
    }
}
