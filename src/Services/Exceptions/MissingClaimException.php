<?php

namespace PebbleAuthClient\Services\Exceptions;

use Throwable;

/**
 * This error should be raised when a claim is missing in the token body.
 */
class MissingClaimException extends \Exception
{
    public function __construct(string $claim, ?Throwable $previous = null)
    {
        $message = "Missing claim $claim.";

        parent::__construct($message, 0, $previous);
    }
}