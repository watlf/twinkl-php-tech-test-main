<?php

declare(strict_types=1);

namespace App\Validator\Rules;

abstract class AbstractValidationRule
{
    protected string $errorMessage;

    public function __construct(
        protected string $fieldName,
        protected array $dataToValidate,
    ) {
    }

    abstract public function validate(): ?string;

    public function getFieldName(): string
    {
        return $this->fieldName;
    }
}
