<?php

namespace TLabsCo\ConfigWalker;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TLabsCo\ConfigWalker\Commands\ConfigWalkerCommand;

class ConfigWalkerServiceProvider extends PackageServiceProvider
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
            ->hasViews()
            ->hasMigration('create_config_walker_table')
            ->hasCommand(ConfigWalkerCommand::class);
    }
}
