<?php

declare(strict_types=1);

namespace App\Model;

abstract class AbstractModel
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct(array $data = [])
    {
        $this->populateModel($data);
    }

    abstract public function toArray(): array;

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getTableName(): string
    {
        return $this->table;
    }

    private function populateModel(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key) and method_exists($this, "set$key")) {
                $this->{"set$key"}($value);
            }
        }
    }
}
