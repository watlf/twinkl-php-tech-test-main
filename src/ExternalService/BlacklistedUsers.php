<?php

declare(strict_types=1);

namespace App\ExternalService;

class BlacklistedUsers implements BlacklistedUsersInterface
{
    public function getBlockedIPs(): array
    {
        return [
            '192.168.1.10',
            '10.0.0.1',
        ];
    }
}
