<?php

return [
    'navigation' => [
        'label' => 'إدارة المستخدمين',
    ],
    'model_label' => 'مستخدم',
    'plural_model_label' => 'مستخدمين',
    'fields' => [
        'name' => 'الاسم بالكامل',
        'username' => 'اسم المستخدم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone_number' => 'رقم الجوال',
        'gender' => 'الجنس',
        'academic_year' => 'السنة الدراسية',
        'school_name' => 'المدرسة',
        'specialization' => 'التخصص',
        'status' => 'الحالة',
        'preferred_language' => 'اللغة المفضلة',
        'preferred_theme' => 'المظهر المفضل',
        'roles' => 'الأدوار',
        'avatar' => 'الصورة الشخصية',
    ],
    'gender' => [
        'male' => 'ذكر',
        'female' => 'أنثى',
    ],
    'specialization' => [
        'scientific' => 'علمي',
        'literary' => 'أدبي',
    ],
    'status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'suspended' => 'معلق',
    ],
    'language' => [
        'ar' => 'العربية',
        'en' => 'الإنجليزية',
    ],
    'theme' => [
        'light' => 'فاتح',
        'dark' => 'داكن',
        'system' => 'النظام',
    ],
    'table' => [
        'avatar' => 'الصورة',
        'name' => 'الاسم',
        'specialization' => 'التخصص',
        'role' => 'الدور',
        'created_at' => 'تاريخ الإنشاء',
    ],
];
