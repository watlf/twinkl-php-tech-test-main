<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Strategy;

class StudentEmailContent implements EmailContentStrategyInterface
{
    public function getMessage(array $data = []): string
    {
        return 'Hello student! Welcome to our service.';
    }
}
