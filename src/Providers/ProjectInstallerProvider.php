<?php

namespace hafid\project_installer\Providers;

use Illuminate\Support\ServiceProvider;

class ProjectInstallerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/installer.php',
            'project_installer'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // to publish packege files to the application directory
        $this->publishes([
            __DIR__ . '/../config/installer.php' => config_path('installer.php'),
            __DIR__ . '/../http/middleware/IsInstalled.php' => app_path('Http/Middleware/IsInstalled.php'),
            __DIR__ . '/../resources/css/install.css' => resource_path('css/install.css'),
        ]);

        // to load your routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // database migrations
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //loading translations files
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'courier');

        // loading views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'project_installer');
    }
}
