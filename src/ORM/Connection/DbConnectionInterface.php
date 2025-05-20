<?php

declare(strict_types=1);

namespace App\ORM\Connection;

interface DbConnectionInterface
{
    public function getConnection(): \PDO;
}
