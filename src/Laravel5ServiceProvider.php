<?php

namespace Ferrisbane\Basket;

use Illuminate\Support\ServiceProvider;

class Laravel5ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Set the directory to load views from
        $this->loadViewsFrom(__DIR__ . '/../views', 'ferrisbane-basket');

        // Set the files to publish
        $this->publishes([
            __DIR__ . '/../config/ferrisbane-basket.php' => config_path('ferrisbane-basket.php')
        ], 'ferrisbane-basket');

        $this->mergeConfigFrom(__DIR__ . '/../config/ferrisbane-basket.php', 'ferrisbane-basket');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->alias('Ferrisbane\Basket\Basket', 'ferrisbane.basket');
    }

    /**
     * Get the package config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return config('ferrisbane-basket');
    }
}
