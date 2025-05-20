<?php

declare(strict_types=1);

namespace App\Service\SendEmail;

use App\Model\UserRole;
use App\Service\SendEmail\Strategy\EmailContentStrategyInterface;
use App\Service\SendEmail\Strategy\ParentEmailContent;
use App\Service\SendEmail\Strategy\PrivateTutorEmailContent;
use App\Service\SendEmail\Strategy\StudentEmailContent;
use App\Service\SendEmail\Strategy\TeacherEmailContent;

class EmailStrategyFactory
{
    public function getEmailStrategyForRole(string $role): EmailContentStrategyInterface
    {
        return match ($role) {
            UserRole::STUDENT->value => new StudentEmailContent(),
            UserRole::TEACHER->value => new TeacherEmailContent(),
            UserRole::PARENT->value => new ParentEmailContent(),
            UserRole::TUTOR->value => new PrivateTutorEmailContent(),
            default => throw new \Exception('Unknown role for email strategy'),
        };
    }
}
