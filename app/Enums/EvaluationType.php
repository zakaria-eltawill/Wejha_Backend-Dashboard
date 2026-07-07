<?php

declare(strict_types=1);

namespace App\Enums;

enum EvaluationType: string
{
    case PRE = 'pre';
    case POST = 'post';

    public function labelAr(): string
    {
        return match ($this) {
            self::PRE => 'تقييم قبلي',
            self::POST => 'تقييم بعدي',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::PRE => 'Pre-Assessment',
            self::POST => 'Post-Assessment',
        };
    }
}
