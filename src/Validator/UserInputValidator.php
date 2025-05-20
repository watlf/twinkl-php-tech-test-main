<?php

declare(strict_types=1);

namespace App\Validator;

use App\ExternalService\BlacklistedUsersInterface;
use App\Validator\Rules\EmailValidationRule;
use App\Validator\Rules\IpValidationRule;
use App\Validator\Rules\NoSpecialCharsRule;
use App\Validator\Rules\RoleTypeValidationRule;

readonly class UserInputValidator
{
    public function __construct(
        private BlacklistedUsersInterface $blacklistedUsers,
        private ValidatorInterface $validator,
    ) {
    }

    public function validateUserInput(array $requestData): array
    {
        $this->validator->addRule(new IpValidationRule($this->blacklistedUsers));
        $this->validator->addRule(new EmailValidationRule('email', $requestData));
        $this->validator->addRule(new NoSpecialCharsRule('firstName', $requestData));
        $this->validator->addRule(new NoSpecialCharsRule('lastName', $requestData));
        $this->validator->addRule(new RoleTypeValidationRule('role', $requestData));

        return $this->validator->validate();
    }
}
