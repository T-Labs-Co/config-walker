<?php

/*
 * This file is a part of package t-co-labs/config-walker
 *
 * (c) T.Labs & Co.
 * Contact for Work: T. <hongty.huynh@gmail.com>
 *
 * We're PHP and Laravel whizzes, and we'd love to work with you! We can:
 *  - Design the perfect fit solution for your app.
 *  - Make your code cleaner and faster.
 *  - Refactoring and Optimize performance.
 *  - Ensure Laravel best practices are followed.
 *  - Provide expert Laravel support.
 *  - Review code and Quality Assurance.
 *  - Offer team and project leadership.
 *  - Delivery Manager
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Support\Str;

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
     * @return ($key is null ? \TLabsCo\ConfigWalker\Facades\ConfigWalker : ($key is string ? mixed : null))
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
     *
     * @param  string  $character
     */
    function camel2dashed(string $str, $character = '-'): string
    {
        return strtolower(preg_replace('/([^A-Z-])([A-Z])/', "$1{$character}$2", $str));
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
