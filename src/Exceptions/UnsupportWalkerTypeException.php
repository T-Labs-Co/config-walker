<?php

namespace TLabsCo\ConfigWalker\Exceptions;

use Exception;

final class UnsupportWalkerTypeException extends Exception
{
    protected $message = '[ConfigWalker] Unsupport type';

    public function __toString()
    {
        return __CLASS__.": {$this->message}\n";
    }
}
