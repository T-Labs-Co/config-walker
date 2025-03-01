<?php

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
        return array_map(fn ($case) => $case['value'], self::cases());
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
        $case = self::fromValue($input);

        return $case && $case->value === $this->value;
    }
}
