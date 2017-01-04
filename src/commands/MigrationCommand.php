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

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'able:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration following the Able specifications.';

    /**
     * Suffix of the migration name.
     *
     * @var string
     */
    protected $migrationSuffix = 'able_setup_tables';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->laravel->view->addNamespace('able', substr(__DIR__, 0, -8) . 'views');

        $rolesTable = Config::get('able.roles_table');
        $groupsTable = Config::get('able.groups_table');
        $groupUserTable = Config::get('able.group_user_table');
        $roleGroupTable = Config::get('able.role_group_table');
        $permissionsTable = Config::get('able.permissions_table');
        $permissionRoleTable = Config::get('able.permission_role_table');

        $this->line('');
        $this->info("Tables: $rolesTable, $groupsTable, $groupUserTable, $roleGroupTable, $permissionsTable, $permissionRoleTable");

        $message = $this->generateMigrationMessage(
            $rolesTable,
            $groupsTable,
            $groupUserTable,
            $roleGroupTable,
            $permissionsTable,
            $permissionRoleTable
        );

        $this->comment($message);

        $existingMigrations = $this->alreadyExistingMigrations();

        if ($existingMigrations) {
            $this->line('');

            $this->warn($this->getExistingMigrationsWarning($existingMigrations));
        }

        $this->line('');

        if (!$this->confirm("Proceed with the migration creation?", "yes")) {
            return;
        }

        $this->line('');

        $this->info("Creating migration...");

        if ($this->createMigration()) {
            $this->info("Migration successfully created!");
        } else {
            $this->error(
                "Couldn't create migration.\n" .
                "Check the write permissions within the database/migrations directory."
            );
        }

        $this->line('');
    }

    /**
     * Create the migration.
     *
     * @param  string $rolesTable
     * @param  string $roleUserTable
     * @param  string $permissionsTable
     * @param  string $permissionRoleTable
     * @return bool
     */
    protected function createMigration()
    {
        $migrationPath = $this->getMigrationPath();

        $userModel = Config::get('auth.providers.users.model');
        $user = new $userModel;
        $able = Config::get('able');

        $data = compact(
            'user',
            'able'
        );

        $output = $this->laravel->view->make('able::generators.migration')->with($data)->render();

        if (!file_exists($migrationPath) && $fs = fopen($migrationPath, 'x')) {
            fwrite($fs, $output);
            fclose($fs);
            return true;
        }

        return false;
    }

    /**
     * Generate the message to display when running the
     * console command showing what tables are going
     * to be created.
     *
     * @param $rolesTable
     * @param $groupsTable
     * @param $groupUserTable
     * @param $roleGroupTable
     * @param $permissionsTable
     * @param $permissionRoleTable
     * @return string
     */
    protected function generateMigrationMessage($rolesTable, $groupsTable, $groupUserTable, $roleGroupTable, $permissionsTable, $permissionRoleTable)
    {
        return "A migration that creates '$rolesTable', '$groupsTable', '$groupUserTable', '$roleGroupTable', '$permissionsTable', '$permissionRoleTable'" .
        " tables will be created in database/migrations directory";
    }

    /**
     * Build a warning regarding possible duplication
     * due to already existing migrations
     *
     * @param  array $existingMigrations
     * @return string
     */
    protected function getExistingMigrationsWarning(array $existingMigrations)
    {
        if (count($existingMigrations) > 1) {
            $base = "Able migrations already exist.\nFollowing files were found: ";
        } else {
            $base = "Able migration already exists.\nFollowing file was found: ";
        }

        return $base . array_reduce($existingMigrations, function ($carry, $fileName) {
            return $carry . "\n - " . $fileName;
        });
    }

    /**
     * Check if there is another migration
     * with the same suffix.
     *
     * @return array
     */
    protected function alreadyExistingMigrations()
    {
        $matchingFiles = glob($this->getMigrationPath('*'));

        return array_map(function ($path) {
            return basename($path);
        }, $matchingFiles);
    }

    /**
     * Get the migration path.
     *
     * The date parameter is optional for ability
     * to provide a custom value or a wildcard.
     *
     * @param  string|null $date
     * @return string
     */
    protected function getMigrationPath($date = null)
    {
        $date = $date ?: date('Y_m_d_His');

        return database_path("migrations/${date}_{$this->migrationSuffix}.php");
    }
}
