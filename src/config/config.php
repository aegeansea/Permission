<?php

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Able Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by Able to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'App\Role',

    /*
    |--------------------------------------------------------------------------
    | Able Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by Able to save roles to the database.
    |
    */
    'roles_table' => 'roles',

    /*
    |--------------------------------------------------------------------------
    | Able Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by Able to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'App\Permission',

    /*
    |--------------------------------------------------------------------------
    | Able Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by Able to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'permissions',

    /*
    |--------------------------------------------------------------------------
    | Able Groups Model
    |--------------------------------------------------------------------------
    |
    | This is the Group model used by Able to create correct relations.
    | Update the group if it is in a different namespace.
    |
    */
    'group' => 'App\Group',

    /*
    |--------------------------------------------------------------------------
    | Able Groups Table
    |--------------------------------------------------------------------------
    |
    | This is the groups table used by Able to save group to the
    | database.
    |
    */
    'groups_table' => 'groups',

    /*
    |--------------------------------------------------------------------------
    | Able permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Able to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'permission_role',

    /*
    |--------------------------------------------------------------------------
    | Able role_group Table
    |--------------------------------------------------------------------------
    |
    | This is the role_group table used by Able to save assigned roles to the
    | database.
    |
    */
    'role_group_table' => 'role_group',

    /*
    |--------------------------------------------------------------------------
    | Able group_user Table
    |--------------------------------------------------------------------------
    |
    | This is the group_user table used by Able to save relationship
    | between groups and users to the database.
    |
    */
    'group_user_table' => 'group_user',

    /*
    |--------------------------------------------------------------------------
    | Able Companies Table
    |--------------------------------------------------------------------------
    |
    | This is the companies table used by Able to save company to the
    | database.
    |
    */
    'companies_table' => 'companies',

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on Able's group_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'user_id',

    /*
    |--------------------------------------------------------------------------
    | Role Foreign key on Able's role_user and permission_role Tables (Pivot)
    |--------------------------------------------------------------------------
    */
    'role_foreign_key' => 'role_id',

    /*
    |--------------------------------------------------------------------------
    | Group Foreign key on Able's group_user table (Pivot)
    |--------------------------------------------------------------------------
    */
    'group_foreign_key' => 'group_id',

    /*
    |--------------------------------------------------------------------------
    | Permission Foreign key on Able's permission_role Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'permission_foreign_key' => 'permission_id',

    /*
    |--------------------------------------------------------------------------
    | company Foreign key on Able's group_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'company_foreign_key' => 'company_id',
    
    /*
    |--------------------------------------------------------------------------
    | Method to be called in the middleware return case
    | Available: abort|redirect
    |--------------------------------------------------------------------------
    */
    'middleware_handling' => 'abort',

    /*
    |--------------------------------------------------------------------------
    | Parameter passed to the middleware_handling method
    |--------------------------------------------------------------------------
    */
    'middleware_params' => '403',
];
