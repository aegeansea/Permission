<?php

namespace Able;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

use Able\Contracts\AblePermissionInterface;
use Able\Traits\AblePermissionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AblePermission extends Model implements AblePermissionInterface
{
    use AblePermissionTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('able.permissions_table');
    }
}
