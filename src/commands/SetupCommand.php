<?php

namespace Able;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

use Illuminate\Console\Command;

class SetupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'able:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup migration and models for Able';

    /**
     * Commands to call with their description
     *
     * @var array
     */
    protected $calls = [
        'able:migration' => 'Creating migration',
        'able:make-role' => 'Creating Role model',
        'able:make-permission' => 'Creating Permission model',
        'able:make-group' => 'Creating Group model',
        'able:add-trait' => 'Adding AbleUserTrait to User model'
    ];

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL . $info);
            $this->call($command);
        }
    }
}
