<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validator\Rules\AbstractValidationRule;

interface ValidatorInterface
{
    public function validate(): array;

    public function addRule(AbstractValidationRule $rule): void;
}
