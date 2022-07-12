<?php

return [
    'resources'     => [
        'UserResource'       => \App\Filament\Resources\UserResource::class,
        'RoleResource'       => \App\Filament\Resources\RoleResource::class,
        'PermissionResource' => \App\Filament\Resources\PermissionResource::class,
    ],
    'pages'         => [
        'Profile' => \Phpsa\FilamentAuthentication\Pages\Profile::class
    ],
    'Widgets'       => [
        'LatestUsers' => [
            'enabled' => true,
            'limit'   => 5,
            'sort'    => 0
        ],
    ],
    'preload_roles' => true,
    'impersonate'   => [
        'enabled'  => true,
        'guard'    => 'web',
        'redirect' => '/'
    ]
];
