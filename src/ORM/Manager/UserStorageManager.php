<?php

declare(strict_types=1);

namespace App\ORM\Manager;

use App\Model\User;

class UserStorageManager extends AbstractStorageManager
{
    private string $tableName = User::TABLE_NAME;

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

    public function findByEmail(string $email): array
    {
        $stmt = $this->db->getConnection()
            ->prepare("SELECT * FROM {$this->tableName} WHERE `email` = :val LIMIT 1");
        $stmt->execute(['val' => $email]);

        return $stmt->fetch() ?: [];
    }
}
