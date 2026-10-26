<?php

namespace TLabsCo\ConfigWalker\Providers;

use Illuminate\Support\ServiceProvider;
use TLabsCo\ConfigWalker\ConfigWalker;

final class ConfigWalkerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config-walker.php',
            'config-walker'
        );

        $this->app->scoped('config-walker', function (): ConfigWalker {
            return new ConfigWalker;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/config-walker.php' => config_path('config-walker.php'),
        ], 'config-walker-config');
    }
}
