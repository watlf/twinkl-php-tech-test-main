<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Provider;

interface EmailSenderInterface
{
    public function send(string $email, string $message): void;
}
