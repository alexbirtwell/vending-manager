<?php

return [
    'models'              => [
        'User'              => \App\Models\User::class,
        'Role'              => \Spatie\Permission\Models\Role::class,
        'Permission'        => \Spatie\Permission\Models\Permission::class,
        'AuthenticationLog' => \Phpsa\FilamentAuthentication\Models\AuthenticationLog::class,
        'PasswordRenewLog'  => \Phpsa\FilamentAuthentication\Models\PasswordRenewLog::class,
    ],
    'resources'           => [
        'UserResource'              => \App\Filament\Resources\UserResource::class,
        'RoleResource'              => \App\Filament\Resources\RoleResource::class,
        'PermissionResource'        => \App\Filament\Resources\PermissionResource::class,
        'AuthenticationLogResource' => \Phpsa\FilamentAuthentication\Resources\AuthenticationLogResource::class,
    ],
    'pages'         => [
        'Profile' => \Phpsa\FilamentAuthentication\Pages\Profile::class
    ],
    'navigation'          => [
        'user'               => [
            'register' => true,
            'sort'     => 1,
            'icon'     => 'heroicon-o-user'
        ],
        'role'               => [
            'register' => true,
            'sort'     => 3,
            'icon'     => 'heroicon-o-user-group'
        ],
        'permission'         => [
            'register' => true,
            'sort'     => 4,
            'icon'     => 'heroicon-o-lock-closed'
        ],
        'authentication_log' => [
            'register' => false,
            'sort'     => 2,
            'icon'     => 'heroicon-o-shield-check'
        ],
    ],
    'Widgets'       => [
        'LatestUsers' => [
            'enabled' => true,
            'limit'   => 5,
            'sort'    => 0
        ],
    ],
    'preload_roles' => true,
    'preload_permissions' => true,
    'impersonate'   => [
        'enabled'  => true,
        'guard'    => 'web',
        'redirect' => '/'
    ],
    'soft_deletes'        => false,
    'authentication_log'  => [
        'table_name'    => 'authentication_log',
        'db_connection' => null,
        'prune'         => 365,
    ],
    'password_renew'      => [
        'table_name'                 => 'password_renew_log',
        'db_connection'              => null,
        'prune'                      => 365,
        'renew_password_days_period' => 90,
        'prevent_password_reuse'     => 0,
    ],
];
