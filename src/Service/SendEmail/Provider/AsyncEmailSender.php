<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Provider;

use Psr\Log\LoggerInterface;

readonly class AsyncEmailSender implements EmailSenderInterface
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function send(string $email, string $message): void
    {
        $this->logger->info('Sending email', [
            'message' => $message,
            'email' => $email,
        ]);
    }
}
