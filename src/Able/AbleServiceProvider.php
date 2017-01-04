<?php

namespace Able;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;

class AbleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migration' => 'command.able.migration',
        'MakeRole' => 'command.able.make-role',
        'MakePermission' => 'command.able.make-permission',
        'MakeGroup' => 'command.able.make-group',
        'AddAbleUserTraitUse' => 'command.able.add-trait',
        'Setup' => 'command.able.setup',
        'MakeSeeder' => 'command.able.seeder'
    ];

    /**
     * Bootstrap the application events.
     *
     * @param   $view
     * @return void
     */
    public function boot()
    {
        // Register published configuration.
        $this->publishes([
            __DIR__.'/../config/config.php' => app()->basePath() . '/config/able.php',
            __DIR__.'/../config/able_seeder.php' => app()->basePath() . '/config/able_seeder.php',
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAble();

        $this->registerCommands();

        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerAble()
    {
        $this->app->bind('able', function ($app) {
            return new Able($app);
        });

        $this->app->alias('able', 'Able\Able');
    }

    /**
     * Register the given commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }
    
    protected function registerMigrationCommand()
    {
        $this->app->singleton('command.able.migration', function () {
            return new MigrationCommand();
        });
    }
    
    protected function registerMakeRoleCommand()
    {
        $this->app->singleton('command.able.make-role', function ($app) {
            return new MakeRoleCommand($app['files']);
        });
    }
    
    protected function registerMakeGroupCommand()
    {
        $this->app->singleton('command.able.make-group', function ($app) {
            return new MakeGroupCommand($app['files']);
        });
    }

    protected function registerMakePermissionCommand()
    {
        $this->app->singleton('command.able.make-permission', function ($app) {
            return new MakePermissionCommand($app['files']);
        });
    }
    
    protected function registerAddAbleUserTraitUseCommand()
    {
        $this->app->singleton('command.able.add-trait', function () {
            return new AddAbleUserTraitUseCommand();
        });
    }
    
    protected function registerSetupCommand()
    {
        $this->app->singleton('command.able.setup', function () {
            return new SetupCommand();
        });
    }

    protected function registerMakeSeederCommand()
    {
        $this->app->singleton('command.able.seeder', function () {
            return new MakeSeederCommand();
        });
    }

    /**
     * Merges user's and able's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'able'
        );
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }
}
