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

trait AbleGroupTrait
{
    /**
     * Big block of caching functionality
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function cachedRoles()
    {
        $cacheKey = 'able_roles_for_group_' . $this->getKey();

        return Cache::remember($cacheKey, Config::get('cache.ttl', 60), function () {
            return $this->roles()->get();
        });
    }

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(
            Config::get('able.role'),
            Config::get('able.role_group_table'),
            Config::get('able.group_foreign_key'),
            Config::get('able.role_foreign_key')
        );
    }

    /**
     * Many-to-Many relations with user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            Config::get('auth.providers.users.model'),
            Config::get('auth.group_user_table'),
            Config::get('auth.group_foreign_key'),
            Config::get('auth.user_foreign_key')
        );
    }

    /**
     * Boot the permission model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the group model uses soft deletes.
     *
     * @return void|bool
     */
    public static function bootAbleGroupTrait()
    {
        $flushCache = function ($group) {
            $group->flushCache();
            return true;
        };

        // If the group doesn't use SoftDeletes
        if (method_exists(Config::get('able.group'), 'restored')) {
            static::restored($flushCache);
        }

        static::deleted($flushCache);
        static::saved($flushCache);

        static::deleting(function ($group) {
            if (!method_exists(Config::get('able.group'), 'bootSoftDeletes')) {
                $group->roles()->sync([]);
                $group->users()->sync([]);
            }
        });
    }

    /**
     * Checks if the group has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        }

        foreach ($this->cachedRoles() as $role) {
            if ($role->name == $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save the inputted roles.
     *
     * @param mixed $inputRoles
     *
     * @return array
     */
    public function saveRoles($inputRoles)
    {
        // If the inputRoles ist empty it will delete all associations
        $changes = $this->roles()->sync($inputRoles);
        $this->flushCache();

        return $changes;
    }

    /**
     * Attach role to current group.
     *
     * @param object|array $role
     *
     * @return void
     */
    public function attachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
        $this->flushCache();

        return $this;
    }

    /**
     * Detach role form current group.
     *
     * @param object|array $role
     *
     * @return void
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
        $this->flushCache();

        return $this;
    }

    /**
     * Attach multiple roles to current group.
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }

        return $this;
    }

    /**
     * Detach multiple roles from current group
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function detachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->detachRole($role);
        }

        return $this;
    }

    /**
     * Flush the group's cache
     * @return void
     */
    public function flushCache()
    {
        Cache::forget('able_roles_for_group_' . $this->getKey());
    }
}
