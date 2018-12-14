<?php

namespace PrimitiveSocial\BlueMoonWrapper;

use Illuminate\Support\ServiceProvider;

class BlueMoonWrapperServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'primitivesocial');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'primitivesocial');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/bluemoonwrapper.php', 'bluemoonwrapper');

        // Register the service the package provides.
        $this->app->singleton('bluemoonwrapper', function ($app) {
            return new BlueMoonWrapper;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['bluemoonwrapper'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/bluemoonwrapper.php' => config_path('bluemoonwrapper.php'),
        ], 'bluemoonwrapper.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/primitivesocial'),
        ], 'bluemoonwrapper.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/primitivesocial'),
        ], 'bluemoonwrapper.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/primitivesocial'),
        ], 'bluemoonwrapper.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
