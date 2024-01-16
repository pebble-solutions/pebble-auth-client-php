<?php

namespace PebbleAuthClient\Services\Exceptions;

use Throwable;

/**
 * This error should be raised when a claim value is invalid in the token body.
 */
class InvalidClaimValueException extends \Exception
{
    public function __construct(string $claim, mixed $value, ?Throwable $previous = null)
    {
        $message = "Invalid value for claim $claim.";

        if ($value) {
            $message .= " Must be $value.";
        }

        parent::__construct($message, 0, $previous);
    }
}