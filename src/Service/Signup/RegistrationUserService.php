<?php

declare(strict_types=1);

namespace App\Service\Signup;

use App\Exception\UserAlreadyExistsException;
use App\Exception\ValidationException;
use App\Model\User;
use App\ORM\Manager\UserStorageManager;
use App\Service\SendEmail\EmailStrategyFactory;
use App\Service\SendEmail\Provider\EmailSenderInterface;
use App\Validator\UserInputValidator;

class RegistrationUserService
{
    public function __construct(
        private UserStorageManager $userStorageManager,
        private EmailSenderInterface $emailSender,
        private UserInputValidator $validator,
    ) {
    }

    public function registerUser(array $requestData): void
    {
        $errors = $this->validator->validateUserInput($requestData);

        if (!empty($errors)) {
            throw new ValidationException(implode("\n", $errors));
        }

        $user = new User($requestData);

        if ($this->userStorageManager->findByEmail($user->getEmail())) {
            throw new UserAlreadyExistsException("User already registered with such email {$user->getEmail()}");
        }

        $this->userStorageManager->create($user);

        $emailContentFactory = new EmailStrategyFactory();

        $emailContentStrategy = $emailContentFactory->getEmailStrategyForRole($user->getRole());

        $this->emailSender->send($user->getEmail(), $emailContentStrategy->getMessage());
    }
}
