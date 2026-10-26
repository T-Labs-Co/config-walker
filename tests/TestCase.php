<?php

namespace TLabsCo\ConfigWalker\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TLabsCo\ConfigWalker\Providers\ConfigWalkerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ConfigWalkerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
