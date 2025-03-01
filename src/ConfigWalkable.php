<?php

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Str;

/**
 * Trait ConfigWalkable
 */
trait ConfigWalkable
{
    public function walkable(): array
    {
        return self::query()->take(10)->pluck($this->primaryKey)->toArray();
    }

    public function walkerKey(): string
    {
        return Str::snake(get_class($this));
    }
}
