<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Strategy;

class ParentEmailContent implements EmailContentStrategyInterface
{
    public function getMessage(array $data = []): string
    {
        return "Hello parent! We're glad to have you.";
    }
}
