<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Signup;

use App\ExternalService\BlacklistedUsersInterface;
use App\ORM\Manager\UserStorageManager;
use App\Service\SendEmail\Provider\EmailSenderInterface;
use App\Service\Signup\RegistrationUserService;
use App\Validator\UserInputValidator;
use App\Validator\ValidatorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RegistrationUserServiceTest extends TestCase
{
    private RegistrationUserService $registrationUserService;
    private MockObject $validator;
    private MockObject $emailSender;
    private MockObject $userStorageManager;

    public function setUp(): void
    {
        $this->userStorageManager = $this->getMockBuilder(UserStorageManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findByEmail', 'create'])
            ->getMock();

        $this->emailSender = $this->createMock(EmailSenderInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $ipBlacklisted = $this->createMock(BlacklistedUsersInterface::class);
        $userInputValidationService = new UserInputValidator($ipBlacklisted, $this->validator);

        $this->registrationUserService = new RegistrationUserService(
            $this->userStorageManager,
            $this->emailSender,
            $userInputValidationService
        );
    }

    public function testRegisterUser(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->userStorageManager->expects($this->once())->method('findByEmail')->willReturn([]);
        $this->userStorageManager->expects($this->once())->method('create')->willReturn(true);
        $this->emailSender->expects($this->once())->method('send');

        $this->registrationUserService->registerUser($this->getRequestData());
    }

    public function testRegisterWhenUserAlreadyExssts(): void
    {
        $this->validator->expects($this->once())->method('validate')->willReturn([]);
        $this->userStorageManager->expects($this->once())->method('findByEmail')->willReturn(['existed user']);
        $this->userStorageManager->expects($this->never())->method('create');
        $this->emailSender->expects($this->never())->method('send');

        $this->expectExceptionMessage('User already registered with such email');

        $this->registrationUserService->registerUser($this->getRequestData());
    }

    public function testValidationException(): void
    {
        $validationMessage = 'Validation message';

        $this->validator->expects($this->once())->method('validate')->willReturn([
            'role' => $validationMessage,
        ]);

        $this->userStorageManager->expects($this->never())->method('findByEmail');
        $this->userStorageManager->expects($this->never())->method('create');
        $this->emailSender->expects($this->never())->method('send');

        $this->expectExceptionMessage($validationMessage);

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
