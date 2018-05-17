<?php

return [
    'role_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,d',
            'post' => 'c,r,u,d,p',
            'category' => 'c,r,u,d',
            'permission' => 'c,r,u,d',
            'roles' => 'c,r,u,d',
        ],
        'guest' => [
        ],
        'administrator' => [
            'users' => 'c,r,u,d',
            'post' => 'c,r,u,d,p',
        ],
        'author' => [
            'post' => 'c,r,u,d',
        ],
         'member' => [
        ],
    ],
    'permission_structure' => [
        'cru_user' => [
            'post' => 'r'
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'p' => 'publish',
    ]
];
