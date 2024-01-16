<?php

namespace PebbleAuthClient\Services\Exceptions;

use Throwable;

/**
 * This error should be raised when the token is not provided or empty.
 */
class EmptyTokenException extends \Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct("Empty token.", 0, $previous);
    }
}