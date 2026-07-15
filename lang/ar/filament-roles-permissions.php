<?php

return [
    'roles' => [
        'navigation' => [
            'label' => 'الأدوار',
        ],
        'model_label' => 'دور',
        'plural_model_label' => 'أدوار',
        'fields' => [
            'name' => 'اسم الدور',
            'guard_name' => 'الحارس',
            'permissions' => 'الصلاحيات الممنوحة',
        ],
        'table' => [
            'name' => 'اسم الدور',
            'guard_name' => 'الحارس',
            'permissions' => 'الصلاحيات',
        ],
    ],
    'permissions' => [
        'navigation' => [
            'label' => 'الأذونات',
        ],
        'model_label' => 'إذن',
        'plural_model_label' => 'أذونات',
        'fields' => [
            'name' => 'اسم الصلاحية',
            'guard_name' => 'الحارس',
        ],
        'table' => [
            'name' => 'اسم الصلاحية',
            'guard_name' => 'الحارس',
        ],
    ],
];
