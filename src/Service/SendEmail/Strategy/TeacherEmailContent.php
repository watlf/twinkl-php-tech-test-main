<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Strategy;

class TeacherEmailContent implements EmailContentStrategyInterface
{
    public function getMessage(array $data = []): string
    {
        return 'Hello teacher! Thank you for joining us.';
    }
}
