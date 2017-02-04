<?php

namespace App\Providers;

use Orangehill\Iseed\Iseed;
use Illuminate\Support\ServiceProvider;

class IseedServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResources();

        $this->app['iseed'] = $this->app->share(function ($app) {
            return new Iseed;
        });

        $this->app->alias('Iseed', 'Orangehill\Iseed\Facades\Iseed');

        $this->app['command.iseed'] = $this->app->share(function ($app) {
            return new \App\Console\Commands\Iseed();
        });
        $this->commands('command.iseed');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('iseed');
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $config = [
            'path' => '/database/seeds',
            'chunk_size' => 500 // Maximum number of rows per insert statement];
        ];

        $this->app['config']->set('iseed::config', $config);
    }
}