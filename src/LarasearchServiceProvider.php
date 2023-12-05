<?php

namespace Clydescobidal\Larasearch;

use Clydescobidal\Larasearch\Console\MakeModelsSearchable;
use Illuminate\Support\ServiceProvider;

class LarasearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/larasearch.php' => config_path('larasearch.php'),
        ]);

        if (file_exists(config_path('larasearch.php'))) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModelsSearchable::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
