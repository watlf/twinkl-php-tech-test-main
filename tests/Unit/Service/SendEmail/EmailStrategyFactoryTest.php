<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\SendEmail;

use App\Model\UserRole;
use App\Service\SendEmail\EmailStrategyFactory;
use PHPUnit\Framework\TestCase;

class EmailStrategyFactoryTest extends TestCase
{
    private EmailStrategyFactory $strategyFactory;

    public function setUp(): void
    {
        $this->strategyFactory = new EmailStrategyFactory();
    }

    /**
     * @dataProvider getUserRoles()
     */
    public function testContentFactory(UserRole $role, string $expectedEmailContent): void
    {
        $message = $this->strategyFactory->getEmailStrategyForRole($role->value);

        $this->assertSame($expectedEmailContent, $message->getMessage());
    }

    public static function getUserRoles(): array
    {
        return [
            'student content' => [UserRole::STUDENT, 'Hello student! Welcome to our service.'],
            'tutor content' => [UserRole::TUTOR, 'Hello tutor! Welcome aboard.'],
            'teacher content' => [UserRole::TEACHER, 'Hello teacher! Thank you for joining us.'],
            'parent content' => [UserRole::PARENT, "Hello parent! We're glad to have you."],
        ];
    }
}
