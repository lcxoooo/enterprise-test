<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Translation\TranslatorInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TranslatorInterface::class, function ($app) {
            return $app['translator'];
        });

        if ($this->app->environment() == 'local') {
            //Databse-Seed
            $this->app->register(\App\Providers\IseedServiceProvider::class);
        }
    }
}
