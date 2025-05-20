<?php

declare(strict_types=1);

namespace App\Validator\Rules;

use App\ExternalService\BlacklistedUsersInterface;

class IpValidationRule extends AbstractValidationRule
{
    public function __construct(
        private readonly BlacklistedUsersInterface $blacklistedUsers,
    ) {
        parent::__construct('REMOTE_ADDR', $_SERVER);
    }

    protected string $errorMessage = 'IP address is blocked';

    public function validate(): ?string
    {
        if (
            !isset($this->dataToValidate[$this->fieldName])
            || in_array($this->dataToValidate[$this->fieldName], $this->blacklistedUsers->getBlockedIPs(), true)
        ) {
            return $this->errorMessage;
        }

        return null;
    }
}
