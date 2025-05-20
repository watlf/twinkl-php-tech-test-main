<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\ExternalService\BlacklistedUsersInterface;
use App\ORM\Manager\UserStorageManager;
use App\Validator\Rules\AbstractValidationRule;
use App\Validator\Rules\EmailValidationRule;
use App\Validator\Rules\IpValidationRule;
use App\Validator\Rules\NoSpecialCharsRule;
use App\Validator\Rules\RoleTypeValidationRule;
use App\Validator\Rules\UniqueUserEmailValidationRule;
use App\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    private Validator $validator;

    public function setUp(): void
    {
        $this->validator = new Validator();
    }

    /**
     * @dataProvider getValidationRules
     */
    public function testValidation(AbstractValidationRule $rule, array $expectedErrorMessage): void
    {
        $this->validator->addRule($rule);

        $actualError = $this->validator->validate();

        $this->assertSame($expectedErrorMessage, $actualError);
    }

    public function testIpAddressBlock(): void
    {
        $ipBlacklisted = $this->createMock(BlacklistedUsersInterface::class);

        $ipBlacklisted->expects($this->once())->method('getBlockedIPs')->willReturn([
            '127.0.0.1',
        ]);

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $rule = new IpValidationRule($ipBlacklisted);

        $this->validator->addRule($rule);

        $actualError = $this->validator->validate();

        $this->assertNotEmpty($actualError);
        $this->assertSame('IP address is blocked', current($actualError));
    }

    public function testUserAlreadyExists(): void
    {
        $userStorageManager = $this->getMockBuilder(UserStorageManager::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findByEmail'])
            ->getMock();

        $userStorageManager->expects($this->once())->method('findByEmail')->willReturn([
            'id' => 1,
            'firstName' => 'John',
            'lastName' => 'Dou',
            'email' => 'dou@mail.com',
            'role' => 'student',
        ]);

        $rule = new UniqueUserEmailValidationRule(
            'email',
            ['email' => 'dou@mail.com'],
            $userStorageManager
        );

        $this->validator->addRule($rule);
        $actualError = $this->validator->validate();

        $this->assertNotEmpty($actualError);
        $this->assertSame('User with sush email already registered', current($actualError));
    }

    public static function getValidationRules(): array
    {
        return [
            'email address is invalid' => [
                new EmailValidationRule('email', ['email' => 'invalid-email']),
                ['email' => 'Email address is invalid'],
            ],
            'email address valid' => [
                new EmailValidationRule('email', ['email' => 'bart@gmail.com']),
                [], // no errors
            ],
            'firstName is invalid' => [
                new NoSpecialCharsRule('firstName', ['firstName' => '!@Andrii']),
                ['firstName' => 'Not valid input'],
            ],
            'firstName valid' => [
                new NoSpecialCharsRule('firstName', ['firstName' => 'Tom']),
                [], // no errors
            ],
            'role is invalid' => [
                new RoleTypeValidationRule('role', ['role' => 'driver']),
                ['role' => 'Not valid choice for `role`'], // no errors
            ],
            'role is valid' => [
                new RoleTypeValidationRule('role', ['role' => 'student']),
                [], // no errors
            ],
        ];
    }
}
