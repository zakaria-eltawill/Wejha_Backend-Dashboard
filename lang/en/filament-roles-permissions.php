<?php

return [
    'roles' => [
        'navigation' => [
            'label' => 'Roles',
        ],
        'model_label' => 'Role',
        'plural_model_label' => 'Roles',
        'fields' => [
            'name' => 'Role Name',
            'guard_name' => 'Guard',
            'permissions' => 'Permissions',
        ],
        'table' => [
            'name' => 'Role Name',
            'guard_name' => 'Guard',
            'permissions' => 'Permissions',
        ],
    ],
    'permissions' => [
        'navigation' => [
            'label' => 'Permissions',
        ],
        'model_label' => 'Permission',
        'plural_model_label' => 'Permissions',
        'fields' => [
            'name' => 'Permission Name',
            'guard_name' => 'Guard',
        ],
        'table' => [
            'name' => 'Permission Name',
            'guard_name' => 'Guard',
        ],
    ],
];
