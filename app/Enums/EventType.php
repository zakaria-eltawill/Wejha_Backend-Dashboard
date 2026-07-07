<?php

declare(strict_types=1);

namespace App\Enums;

enum EventType: string
{
    case SEMINAR = 'seminar';
    case WORKSHOP = 'workshop';
    case EXHIBITION = 'exhibition';

    public function labelAr(): string
    {
        return match ($this) {
            self::SEMINAR => 'ندوة',
            self::WORKSHOP => 'ورشة عمل',
            self::EXHIBITION => 'معرض',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::SEMINAR => 'Seminar',
            self::WORKSHOP => 'Workshop',
            self::EXHIBITION => 'Exhibition',
        };
    }
}
