<?php

declare(strict_types=1);

namespace App\Enums;

enum QuestionType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case RATING = 'rating';
    case MULTIPLE_CHOICE = 'multiple_choice';
    case CHECKBOX = 'checkbox';
    case NUMBER = 'number';
    case DATE = 'date';
    case EMAIL = 'email';
    case PHONE = 'phone';

    public function labelAr(): string
    {
        return match ($this) {
            self::TEXT => 'نص قصير',
            self::TEXTAREA => 'نص طويل',
            self::RATING => 'تقييم بالنجوم',
            self::MULTIPLE_CHOICE => 'اختيار من متعدد (خيارات متعددة، جواب واحد)',
            self::CHECKBOX => 'صناديق اختيار (خيارات متعددة، أجوبة متعددة)',
            self::NUMBER => 'رقم',
            self::DATE => 'تاريخ',
            self::EMAIL => 'بريد إلكتروني',
            self::PHONE => 'رقم هاتف',
        };
    }

    public function labelEn(): string
    {
        return match ($this) {
            self::TEXT => 'Text',
            self::TEXTAREA => 'Textarea',
            self::RATING => 'Rating',
            self::MULTIPLE_CHOICE => 'Multiple Choice',
            self::CHECKBOX => 'Checkbox',
            self::NUMBER => 'Number',
            self::DATE => 'Date',
            self::EMAIL => 'Email',
            self::PHONE => 'Phone',
        };
    }
}
