<?php

declare(strict_types=1);

namespace TLabsCo\ConfigWalker\Exceptions;

use Exception;

final class UnsupportWalkerTypeException extends Exception
{
    protected $message = '[ConfigWalker] Unsupport type';

    public function __toString(): string
    {
        return __CLASS__.": {$this->message}\n";
    }
}
