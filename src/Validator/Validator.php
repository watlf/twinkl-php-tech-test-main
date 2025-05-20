<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Rules\AbstractValidationRule;

class Validator implements ValidatorInterface
{
    /**
     * @var AbstractValidationRule[]
     */
    private array $rules = [];

    public function validate(): array
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            $error = $rule->validate();

            if (!empty($error)) {
                $errors[$rule->getFieldName()] = $error;
            }
        }

        return $errors;
    }

    public function addRule(AbstractValidationRule $rule): void
    {
        $this->rules[] = $rule;
    }
}
