<?php

declare(strict_types=1);

namespace App\Service\SendEmail\Strategy;

interface EmailContentStrategyInterface
{
    public function getMessage(array $data = []): string;
}
