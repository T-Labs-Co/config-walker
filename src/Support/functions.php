<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use TLabsCo\ConfigWalker\Facades\ConfigWalker;

if (! function_exists('config_walker')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * If tag existing then config go under scoped tag
     *
     * @param  array<string, mixed>|string|null  $key
     * @param  mixed  $default
     * @param  mixed  $tag
     * @return ($key is null ? ConfigWalker : ($key is string ? mixed : null))
     */
    function config_walker($key = null, $default = null, $tag = null)
    {
        if (is_null($key)) {
            return app('config-walker');
        }

        if (is_array($key)) {
            return app('config-walker')->walk($key, null, $tag);
        }

        return ! empty($tag) ? app('config-walker')->tag($tag)->get($key, $default) : app('config-walker')->get($key, $default);
    }
}

if (! function_exists('camel2dashed')) {
    /**
     * Convert camelCase to dashed-case
     */
    function camel2dashed(string $str, string $character = '-'): string
    {
        $result = preg_replace('/([^A-Z-])([A-Z])/', "$1{$character}$2", $str);

        return strtolower(is_string($result) ? $result : $str);
    }
}

if (! function_exists('class2camel')) {
    /**
     * Convert class name to camelCase
     */
    function class2camel(string|object $class): string
    {
        return Str::camel(Str::afterLast(is_object($class) ? get_class($class) : $class, '\\'));
    }
}
