<?php

namespace PebbleAuthClient\Datatypes;

use PebbleAuthClient\Interfaces\UserInterface;

class AuthenticatedLicenceObject {
    /**
     * Application for which the licence is generated
     */
    public ?string $app;

    /**
     * Licence server that emits the authorisation
     */
    public ?string $issuer;

    /**
     * Customer id, client id... that will consume resources
     */
    public ?string $tenant_id;

    /**
     * Instance of User class who own the licence
     */
    public UserInterface $user;

    public function __construct(array $values) {
        foreach ($values as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }
}