<?php

declare(strict_types=1);

namespace App\Validator\Rules;

class EmailValidationRule extends AbstractValidationRule
{
    protected string $errorMessage = 'Email address is invalid';

    public function validate(): ?string
    {
        if (
            !isset($this->dataToValidate[$this->fieldName])
            || !filter_var($this->dataToValidate[$this->fieldName], FILTER_VALIDATE_EMAIL)
        ) {
            return $this->errorMessage;
        }

        return null;
    }
}
