<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker\Contracts;

interface ConfigWalkerAware
{
    /** @return array<string, mixed> */
    public function toWalker(): array;

    public function walkerKey(): string;
}
