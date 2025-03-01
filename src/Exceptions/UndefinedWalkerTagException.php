<?php

namespace TLabsCo\ConfigWalker\Exceptions;

use Exception;

final class UndefinedWalkerTagException extends Exception
{
    protected $message = '[ConfigWalker] Undefined tag';

    public function __toString()
    {
        return __CLASS__.": {$this->message}\n";
    }
}
