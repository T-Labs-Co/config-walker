<?php

namespace TLabsCo\ConfigWalker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TLabsCo\ConfigWalker\Commands\ConfigWalkerCommand;

final class ConfigWalkerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('config-walker')
            ->hasConfigFile()
            ->hasCommand(ConfigWalkerCommand::class);

        // register single object for config walker
        $this->app->scoped('config-walker', function () {
            return new ConfigWalker;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
