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
