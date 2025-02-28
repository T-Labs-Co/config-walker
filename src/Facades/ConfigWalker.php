<?php

namespace TLabsCo\ConfigWalker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TLabsCo\ConfigWalker\ConfigWalker
 */
class ConfigWalker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \TLabsCo\ConfigWalker\ConfigWalker::class;
    }
}
