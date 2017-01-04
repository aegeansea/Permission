<?php

namespace Able\Contracts;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

interface AbleGroupInterface
{
    
    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Many-to-Many relations with user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users();

    /**
     * Save the inputted roles.
     *
     * @param mixed $inputRoles
     *
     * @return void
     */
    public function saveRoles($inputRoles);

    /**
     * Attach role to current group.
     *
     * @param object|array $role
     *
     * @return void
     */
    public function attachRole($role);

    /**
     * Detach role form current group.
     *
     * @param object|array $role
     *
     * @return void
     */
    public function detachRole($role);

    /**
     * Attach multiple roles to current group.
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function attachRoles($roles);

    /**
     * Detach multiple roles from current group
     *
     * @param mixed $roles
     *
     * @return void
     */
    public function detachRoles($roles);
}
