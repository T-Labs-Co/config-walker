<?php

namespace TLabsCo\ConfigWalker;

use Illuminate\Support\Str;
use TLabsCo\ConfigWalker\Utils\EnumHelperTrait;

trait EnumConfigWalkable
{
    use EnumHelperTrait;

    public static function walkable(): array
    {
        return self::toArray();
    }

    public static function walkerKey(): string
    {
        // get only class name without namespace
        // from 'App\Enums\StatusEnum' to 'StatusEnum'
        $classNameOnly = Str::afterLast(get_class(self::cases()[0]), '\\');

        // convert camel to dashed or snake case
        // from 'App\Enums\StatusEnum' to 'status_enum'
        return camel2dashed($classNameOnly, '_');
    }
}
