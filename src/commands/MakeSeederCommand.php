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
use Illuminate\Support\Facades\Config;

class MakeSeederCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'able:seeder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the seeder following the Able specifications.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->laravel->view->addNamespace('able', substr(__DIR__, 0, -8).'views');

        if ($this->createSeeder()) {
            $this->info("Seeder successfully created!");
        } else {
            $this->error(
                "Couldn't create seeder.\n".
                "Check the write permissions within the database/seeds directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the seeder
     * @return bool
     */
    protected function createSeeder()
    {
        $permission = Config::get('able.permission', 'App\Permission');
        $role = Config::get('able.role', 'App\Role');
        $rolePermissions = Config::get('able.permission_role_table');
        $userGroups = Config::get('able.group_user_table');
        $roleUsers = Config::get('able.role_user_table');
        $user   = Config::get('auth.providers.users.model', 'App\User');

        $migrationPath = $this->getMigrationPath();
        $output = $this->laravel->view->make('able::generators.seeder')
            ->with(compact([
                'role',
                'permission',
                'user',
                'rolePermissions',
                'userGroups',
                'roleUsers',
            ]))
            ->render();

        if (!file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Get the seeder path.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return database_path("seeds/AbleSeeder.php");
    }
}
