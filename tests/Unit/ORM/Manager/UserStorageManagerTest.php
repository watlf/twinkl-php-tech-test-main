<?php

declare(strict_types=1);

use App\Model\User;
use App\ORM\Connection\DbConnectionInterface;
use App\ORM\Manager\UserStorageManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserStorageManagerTest extends TestCase
{
    private MockObject $db;

    private MockObject $PDO;
    private MockObject $PDOStatement;

    public function setUp(): void
    {
        $this->db = $this->createMock(DbConnectionInterface::class);
        $this->PDO = $this->createMock(PDO::class);
        $this->PDOStatement = $this->createMock(PDOStatement::class);

        $this->db->method('getConnection')->willReturn($this->PDO);
    }

    public function testInsertQueryBuild(): void
    {
        $userStorageManager = new UserStorageManager($this->db);

        $this->PDOStatement->expects($this->once())->method('execute');
        $this->PDO->expects($this->once())->method('prepare')->with(
            'INSERT INTO `users` (first_name,last_name,email,role) VALUES (:first_name,:last_name,:email,:role)'
        )->willReturn($this->PDOStatement);

        $userStorageManager->create($this->getModel());
    }

    public function testUpdateQueryBuild(): void
    {
        $userStorageManager = new UserStorageManager($this->db);

        $this->PDOStatement->expects($this->once())->method('execute');
        $this->PDO->expects($this->once())->method('prepare')->with(
            'UPDATE `users` SET first_name=:first_name,last_name=:last_name,email=:email,role=:role WHERE id = :id'
        )->willReturn($this->PDOStatement);

        $userStorageManager->update(1, $this->getModel());
    }

    public function testSelectAllQueryBuild(): void
    {
        $userStorageManager = new UserStorageManager($this->db);

        $this->PDO->expects($this->once())->method('query')->with(
            'SELECT * FROM users'
        )->willReturn($this->PDOStatement);

        $userStorageManager->getAll(User::TABLE_NAME, User::class);
    }

    private function getModel(): User
    {
        return new User([
            'id' => 1,
            'firstName' => 'Sarah',
            'lastName' => 'Connor',
            'email' => 'sarah.connor@mail.com',
            'role' => 'teacher',
        ]);
    }
}
