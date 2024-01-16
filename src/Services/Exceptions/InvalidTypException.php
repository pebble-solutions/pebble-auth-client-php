<?php

namespace PebbleAuthClient\Services\Exceptions;

use Throwable;

/**
 * This error should be raised when the token typ is invalid. According to standard recommendation, typ for
 * authorization JWT must be at+jwt.
 */
class InvalidTypException extends \Exception
{
    public function __construct(?string $typ, ?Throwable $previous = null)
    {
        $message = "Invalid token typ header.";

        if ($typ) {
            $message .= " Must be $typ.";
        }

        parent::__construct($message, 0, $previous);
    }
}