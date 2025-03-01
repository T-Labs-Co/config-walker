<?php

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

/**
 * Class WalkerParser
 */
final class WalkerParser
{
    /**
     * @param  string|array|object  $walker
     * @param  string|null  $key
     */
    public function parse($walker, $key = null): array
    {
        if ($walker === 'default') {
            $append = Config::all();
        } elseif (is_array($walker)) {
            $append = $walker;
        } elseif ($this->isUsingWalkableTrait($walker) || $this->isWalkerAware($walker)) {
            $append = $this->parseWalkable($walker);
            $key = $this->parseKey($key, $walker);
        } else {
            $append = [$walker];
        }

        if ($key) {
            $append = [$key => $append];
        }

        return $append;
    }

    private function parseWalkable($walker): array
    {
        $obj = $this->makeClassInstance($walker);

        if (method_exists($obj, 'walkable')) {
            return $obj->walkable();
        }

        if (method_exists($obj, 'toWalker')) {
            return $obj->toWalker();
        }

        // empty value
        return [];
    }

    private function parseKey($key, $walker): string
    {
        $obj = $this->makeClassInstance($walker);

        if (! $key && method_exists($obj, 'walkerKey')) {
            $key = $obj->walkerKey();
        }

        if (! $key) {
            $key = Str::snake(get_class($obj));
        }

        return $key;
    }

    private function isWalkerAware(string|object $class): bool
    {
        $obj = $this->makeClassInstance($class);

        return $obj instanceof ConfigWalkerAware;
    }

    private function isUsingWalkableTrait($class): bool
    {
        $obj = $this->makeClassInstance($class);

        return in_array(ConfigWalkable::class, class_uses_recursive($obj));
    }

    private function makeClassInstance($class): mixed
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        return (new \ReflectionClass($class))->isEnum() ? $class::cases()[0] : new $class;
    }
}
