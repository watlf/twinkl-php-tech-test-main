<?php

declare(strict_types=1);

namespace App\ORM\Manager;

class UserStorageManager extends AbstractStorageManager
{
    protected function initializeTable(): void
    {
        $this->db
            ->getConnection()
            ->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            email VARCHAR(100) UNIQUE,
            role VARCHAR(50)
        )');
    }
}
