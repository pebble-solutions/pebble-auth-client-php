<?php

namespace PebbleAuthClient\Services\Exceptions;

use Throwable;

/**
 * This error should be raised when multiple token are provided to the auth process.
 */
class AmbiguousTokenException extends \Exception
{
    public function __construct(?Throwable $previous = null)
    {
        $message = "Provided token is ambiguous. This error can be caused by multiple authorization headers in the 
        request";

        parent::__construct($message, 0, $previous);
    }
}