<?php

namespace Able\Traits;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

use Illuminate\Support\Facades\Config;

trait AblePermissionTrait
{
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            Config::get('able.role'),
            Config::get('able.permission_role_table'),
            Config::get('able.permission_foreign_key'),
            Config::get('able.role_foreign_key')
        );
    }

    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the permission model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootAblePermissionTrait()
    {
        static::deleting(function ($permission) {
            if (!method_exists(Config::get('able.permission'), 'bootSoftDeletes')) {
                $permission->roles()->sync([]);
            }
        });
    }
}
