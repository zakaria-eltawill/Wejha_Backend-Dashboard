<?php

return [
    'staff' => [
        'navigation' => ['label' => 'Staff & Admins'],
        'model_label' => 'Staff User',
        'plural_model_label' => 'Staff Users',
    ],
    'students' => [
        'navigation' => ['label' => 'Students'],
        'model_label' => 'Student',
        'plural_model_label' => 'Students',
    ],
    'super_admin_protected' => 'The Super Admin is protected: their roles cannot be changed and they cannot be deleted from the system.',
    'fields' => [
        'name' => 'Full Name',
        'username' => 'Username',
        'email' => 'Email',
        'password' => 'Password',
        'phone_number' => 'Phone',
        'gender' => 'Gender',
        'academic_year' => 'Year',
        'school_name' => 'School',
        'specialization' => 'Specialization',
        'status' => 'Status',
        'preferred_language' => 'Language',
        'preferred_theme' => 'Preferred Theme',
        'roles' => 'Roles',
        'avatar' => 'Avatar',
    ],
    'gender' => [
        'male' => 'Male',
        'female' => 'Female',
    ],
    'specialization' => [
        'scientific' => 'Scientific',
        'literary' => 'Literary',
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'suspended' => 'Suspended',
    ],
    'language' => [
        'ar' => 'Arabic',
        'en' => 'English',
    ],
    'theme' => [
        'light' => 'Light',
        'dark' => 'Dark',
        'system' => 'System',
    ],
    'table' => [
        'avatar' => 'Avatar',
        'name' => 'Name',
        'specialization' => 'Track',
        'role' => 'Role',
        'created_at' => 'Created',
    ],
];
