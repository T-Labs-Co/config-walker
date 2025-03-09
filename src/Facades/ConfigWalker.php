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

namespace TLabsCo\ConfigWalker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static ConfigWalker walk($walker, $key = null, $tags = null)
 * @method static array all()
 * @method static mixed get($key, $default = null)
 * @method static array getArray($keys, $default = null)
 * @method static void set($key, $value = null)
 * @method static bool has($key)
 * @method static void forget($key)
 * @method static void prepend($key, $value)
 * @method static void push($key, $value)
 * @method static mixed pull($key, $default = null)
 * @method static ConfigWalker tag($tag)
 * @method static ConfigWalker tags($tags, $flat = false)
 * @method static ConfigWalker addTag($tag)
 * @method static ConfigWalker removeTag($tag, $includeConfig = true)
 * @method static bool hasTag($tag)
 * @method static array tagList()
 * @method static ConfigWalker loadDefault()
 *
 * @see \TLabsCo\ConfigWalker\ConfigWalker
 */
final class ConfigWalker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'config-walker';
    }
}
