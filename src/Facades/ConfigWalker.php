<?php

namespace TLabsCo\ConfigWalker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \TLabsCo\ConfigWalker\ConfigWalker walk(mixed $walker, string|null $key = null, string|array<string>|null $tags = null)
 * @method static array<string, mixed> all()
 * @method static mixed get(string|array<string> $key, mixed $default = null)
 * @method static array<string, mixed> getArray(array<string> $keys, mixed $default = null)
 * @method static void set(string|array<string, mixed> $key, mixed $value = null)
 * @method static bool has(mixed $key)
 * @method static void forget(string $key)
 * @method static void prepend(mixed $key, mixed $value)
 * @method static void push(mixed $key, mixed $value)
 * @method static mixed pull(string $key, mixed $default = null)
 * @method static \TLabsCo\ConfigWalker\ConfigWalker tag(string $tag)
 * @method static \TLabsCo\ConfigWalker\ConfigWalker tags(array<string> $tags, bool $flat = false)
 * @method static \TLabsCo\ConfigWalker\ConfigWalker addTag(string $tag)
 * @method static \TLabsCo\ConfigWalker\ConfigWalker removeTag(string $tag, bool $includeConfig = true)
 * @method static bool hasTag(string $tag)
 * @method static array<string, string> tagList()
 * @method static \TLabsCo\ConfigWalker\ConfigWalker loadDefault()
 * @method static \TLabsCo\ConfigWalker\ConfigWalker reset()
 * @method static \TLabsCo\ConfigWalker\ConfigWalker makeDefault()
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
