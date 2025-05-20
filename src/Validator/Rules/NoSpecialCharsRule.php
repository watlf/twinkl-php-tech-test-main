<?php

declare(strict_types=1);

namespace App\Validator\Rules;

class NoSpecialCharsRule extends AbstractValidationRule
{
    protected string $errorMessage = 'Not valid input';

    public function validate(): ?string
    {
        if (
            !isset($this->dataToValidate[$this->fieldName])
            || !preg_match('/^[a-zA-Z0-9\s]+$/', $this->dataToValidate[$this->fieldName])
        ) {
            return $this->errorMessage;
        }

        return null;
    }
}
