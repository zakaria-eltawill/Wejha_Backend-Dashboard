<?php

declare(strict_types=1);

namespace App\Enums;

enum RegistrationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CANCELLED = 'cancelled';
    case CHECKED_IN = 'checked_in';

    public function labelAr(): string
    {
        return match ($this) {
            self::PENDING => 'قيد الانتظار',
            self::APPROVED => 'مقبول',
            self::REJECTED => 'مرفوض',
            self::CANCELLED => 'ملغي',
            self::CHECKED_IN => 'تم تسجيل الحضور',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
            self::CHECKED_IN => 'Checked In',
        };
    }
}
