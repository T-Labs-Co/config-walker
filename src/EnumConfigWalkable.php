<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Str;

trait EnumConfigWalkable
{
    public static function walkable(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function walkerKey(): string
    {
        $classNameOnly = Str::afterLast(get_class(self::cases()[0]), '\\');

        return camel2dashed($classNameOnly, '_');
    }
}
