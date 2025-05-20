<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Exception\ValidationException;
use App\Model\User;
use App\ORM\Manager\UserStorageManager;
use App\Service\SendEmail\EmailStrategyFactory;
use App\Service\SendEmail\Provider\EmailSenderInterface;
use App\Validator\UserInputValidator;

class UserManager
{
    public function __construct(
        private UserStorageManager $userStorageManager,
        private EmailSenderInterface $emailSender,
        private UserInputValidator $validator,
    ) {
    }

    public function registerUser(array $requestData): array
    {
        $errors = $this->validator->validateUserInput($requestData);

        if (!empty($errors)) {
            throw new ValidationException(implode("\n", $errors));
        }

        $user = new User($requestData);

        $newUser = $this->userStorageManager->createUser($user);

        if (!$newUser) {
            throw new \Exception('Cannot create user');
        }

        $emailContentFactory = new EmailStrategyFactory();

        $emailContentStrategy = $emailContentFactory->getEmailStrategyForRole($user->getRole());

        $this->emailSender->send($user->getEmail(), $emailContentStrategy->getMessage());

        return $newUser;
    }
}
