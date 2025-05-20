<?php

declare(strict_types=1);

namespace App\ORM\Manager;

interface UserStorageInterface
{
    public function findByEmail(string $email): array;
}
