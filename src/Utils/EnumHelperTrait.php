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

namespace TLabsCo\ConfigWalker\Utils;

trait EnumHelperTrait
{
    public static function labels(): array
    {
        // convert all values to title case and return
        return array_map(fn ($case) => $case->label(), self::cases());
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function toList(): string
    {
        return implode(',', self::toArray());
    }

    public static function toSelect(): array
    {
        return array_map(fn ($case) => [$case->value => $case->label()], self::cases());
    }

    public function label(): string
    {
        // convert value to title case and return
        return ucwords(str_replace('_', ' ', $this->name));
    }

    public function is(mixed $input): bool
    {
        $case = self::from($input);

        return $case && $case->value === $this->value;
    }
}
