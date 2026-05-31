<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions.
         */
        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles.
         */
        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [

        'role_pivot_key' => null,
        'permission_pivot_key' => null,

        'model_morph_key' => 'model_id',

        'team_foreign_key' => 'team_id',
    ],

    'teams' => false,

    'display_permission_in_exception' => true,

    'display_role_in_exception' => true,

    'enable_wildcard_permission' => false,

    'cache' => [

        'expiration_time' => DateInterval::createFromDateString('24 hours'),

        'key' => 'spatie.permission.cache',

        'store' => 'default',

        'model_key' => App\Models\User::class,

        'permissions' => [
            Spatie\Permission\PermissionRegistrar::class,
        ],
    ],

];
