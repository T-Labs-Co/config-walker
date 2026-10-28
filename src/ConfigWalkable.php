<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Str;

trait ConfigWalkable
{
    public function walkable(): array
    {
        // @phpstan-ignore-next-line
        return self::query()->take(10)->pluck($this->primaryKey)->toArray();
    }

    public function walkerKey(): string
    {
        return Str::snake(get_class($this));
    }
}
