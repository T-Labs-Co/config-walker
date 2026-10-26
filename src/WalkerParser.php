<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use TLabsCo\ConfigWalker\Contracts\ConfigWalkerAware as ConfigWalkerAwareContract;

final class WalkerParser
{
    /**
     * @param  mixed  $walker
     * @return array<string, mixed>
     */
    public function parse($walker, ?string $key = null): array
    {
        if ($walker === 'default') {
            $append = Config::all();
        } elseif (is_array($walker)) {
            $append = $walker;
        } elseif (is_object($walker) && ($this->isUsingWalkableTrait($walker) || $this->isWalkerAware($walker))) {
            $append = $this->parseWalkable($walker);
            $key = $this->parseKey($key, $walker);
        } elseif (is_string($walker) && class_exists($walker) && ($this->isUsingWalkableTrait($walker) || $this->isWalkerAware($walker))) {
            $append = $this->parseWalkable($walker);
            $key = $this->parseKey($key, $walker);
        } else {
            $append = is_object($walker) ? [] : [$walker];
        }

        if ($key) {
            $append = [$key => $append];
        }

        return $append;
    }

    /**
     * @param  string|object  $walker
     * @return array<string, mixed>
     */
    private function parseWalkable($walker): array
    {
        $obj = $this->makeClassInstance($walker);

        if (method_exists($obj, 'walkable')) {
            return $obj->walkable();
        }

        if (method_exists($obj, 'toWalker')) {
            return $obj->toWalker();
        }

        return [];
    }

    /**
     * @param  string|object  $walker
     */
    private function parseKey(?string $key, $walker): string
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

        return $obj instanceof ConfigWalkerAwareContract;
    }

    private function isUsingWalkableTrait(string|object $class): bool
    {
        $obj = $this->makeClassInstance($class);

        return in_array(ConfigWalkable::class, class_uses_recursive($obj))
            || in_array(EnumConfigWalkable::class, class_uses_recursive($obj));
    }

    private function makeClassInstance(string|object $class): object
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        // @phpstan-ignore argument.type
        return (new \ReflectionClass($class))->isEnum() ? $class::cases()[0] : new $class;
    }
}
