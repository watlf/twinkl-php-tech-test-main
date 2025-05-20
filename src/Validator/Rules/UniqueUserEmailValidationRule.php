<?php

declare(strict_types=1);

namespace App\Validator\Rules;

use App\ORM\Manager\UserStorageManager;

class UniqueUserEmailValidationRule extends AbstractValidationRule
{
    protected string $errorMessage = 'User with sush email already registered';

    public function __construct(
        protected string $fieldName,
        protected array $dataToValidate,
        private readonly UserStorageManager $userStorageManager,
    ) {
        parent::__construct('email', $dataToValidate);
    }

    public function validate(): ?string
    {
        if (
            !isset($this->dataToValidate[$this->fieldName])
            || $this->userStorageManager->findByEmail($this->dataToValidate[$this->fieldName])
        ) {
            return $this->errorMessage;
        }

        return null;
    }
}
