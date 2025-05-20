<?php

declare(strict_types=1);

namespace App\ORM\Manager;

use App\Model\AbstractModel;
use App\ORM\Connection\DbConnectionInterface;

abstract class AbstractStorageManager
{
    public function __construct(protected readonly DbConnectionInterface $db)
    {
        $this->initializeTable();
    }

    abstract protected function initializeTable(): void;

    public function create(AbstractModel $model): ?int
    {
        $attributes = $model->toArray();
        $columns = array_keys($attributes);
        $placeholders = array_map(fn ($c) => ":$c", $columns);

        $stmt = $this->db->getConnection()->prepare(
            "INSERT INTO `{$model->getTableName()}` (".implode(',', $columns).') VALUES ('.implode(',', $placeholders).')'
        );

        $this->bindValues($stmt, $attributes);

        return $stmt->execute() ? (int) $this->db->getConnection()->lastInsertId() : null;
    }

    public function update(int $id, AbstractModel $model): bool
    {
        $attributes = $model->toArray();
        $columns = array_keys($attributes);
        $updates = implode(',', array_map(fn ($c) => "$c=:$c", $columns));
        $stmt = $this->db->getConnection()->prepare(
            "UPDATE `{$model->getTableName()}` SET $updates WHERE {$model->getPrimaryKey()} = :id"
        );

        $attributes['id'] = $id;

        $this->bindValues($stmt, $attributes);

        return $stmt->execute();
    }

    public function getAll(string $table): array
    {
        $stmt = $this->db->getConnection()->query("SELECT * FROM {$table}");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function bindValues(\PDOStatement $stmt, array $attributes): void
    {
        foreach ($attributes as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
    }
}
