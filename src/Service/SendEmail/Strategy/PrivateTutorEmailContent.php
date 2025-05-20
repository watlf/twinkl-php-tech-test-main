<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Strategy;

class PrivateTutorEmailContent implements EmailContentStrategyInterface
{
    public function getMessage(array $data = []): string
    {
        return 'Hello tutor! Welcome aboard.';
    }
}
