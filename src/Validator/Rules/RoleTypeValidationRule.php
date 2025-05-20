<?php

namespace App\Validator\Rules;

use App\Model\UserRole;

class RoleTypeValidationRule extends AbstractValidationRule
{
    protected string $errorMessage = 'Not valid choice for `role`';

    public function validate(): ?string
    {
        if (
            !isset($this->dataToValidate[$this->fieldName])
            || !UserRole::match($this->dataToValidate[$this->fieldName])
        ) {
            return $this->errorMessage;
        }

        return null;
    }
}
