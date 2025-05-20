<?php

namespace App\Model;

enum UserRole: string
{
    case TEACHER = 'teacher';
    case STUDENT = 'student';
    case TUTOR = 'tutor';
    case PARENT = 'parent';

    public static function match(string $role): bool
    {
        return match ($role) {
            self::TEACHER->value, self::PARENT->value, self::TUTOR->value, self::STUDENT->value => true,
            default => false,
        };
    }
}
