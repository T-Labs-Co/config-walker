<?php

namespace TLabsCo\ConfigWalker;

/**
 * Interface ConfigWalkerAware
 */
interface ConfigWalkerAware
{
    public function toWalker(): array;

    public function walkerKey(): string;
}
