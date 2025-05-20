<?php

declare(strict_types=1);

namespace App\ORM\Connection;

readonly class MysqlConnection implements DbConnectionInterface
{
    private \PDO $pdo;

    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password,
    ) {
        try {
            $this->pdo = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Database connection failed: '.$e->getMessage());
        }
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}
