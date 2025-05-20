<?php

declare(strict_types=1);

namespace App\ExternalService;

interface BlacklistedUsersInterface
{
    public function getBlockedIPs(): array;
}
