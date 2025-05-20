<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\User;

use App\ExternalService\BlacklistedUsersInterface;
use App\ORM\Manager\UserStorageManager;
use App\Service\SendEmail\Provider\EmailSenderInterface;
use App\Service\User\UserManager;
use App\Validator\UserInputValidator;
use App\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private UserManager $registrationUserService;
    private MockObject $validator;
    private MockObject $emailSender;
    private MockObject $userStorageManager;

    public function setUp(): void
    {
        $this->userStorageManager = $this->getMockBuilder(UserStorageManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createUser'])
            ->getMock();

        $this->emailSender = $this->createMock(EmailSenderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $ipBlacklisted = $this->createMock(BlacklistedUsersInterface::class);
        $userInputValidationService = new UserInputValidator($ipBlacklisted, $this->validator, $this->userStorageManager);

        $this->registrationUserService = new UserManager(
            $this->userStorageManager,
            $this->emailSender,
            $userInputValidationService
        );
    }

    public function testRegisterUser(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->userStorageManager->expects($this->once())->method('createUser')->willReturn([
            'id' => 1,
            'firstName' => 'New',
            'lastName' => 'User',
            'email' => 'newUser@mail.com',
            'role' => 'student',
        ]);
        $this->emailSender->expects($this->once())->method('send');

        $this->registrationUserService->registerUser($this->getRequestData());
    }

    public function testValidationException(): void
    {
        $validationMessage = 'Validation message';

        $this->validator->expects($this->once())->method('validate')->willReturn([
            'role' => $validationMessage,
        ]);

        $this->userStorageManager->expects($this->never())->method('createUser');
        $this->emailSender->expects($this->never())->method('send');
        $this->expectExceptionMessage($validationMessage);

        $this->registrationUserService->registerUser($this->getRequestData());
    }

    public function testUseCreateException(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->userStorageManager->expects($this->once())->method('createUser')->willReturn([]);

        $this->emailSender->expects($this->never())->method('send');
        $this->expectExceptionMessage('Cannot create user');

        $this->registrationUserService->registerUser($this->getRequestData());
    }

    private function getRequestData(): array
    {
        return [
            'firstName' => 'Tom',
            'lastName' => 'Hanks',
            'email' => 'TomHanks@mail.com',
            'role' => 'parent',
        ];
    }
}
