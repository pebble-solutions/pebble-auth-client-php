<?php

namespace PebbleAuthClient\Interfaces;

interface PebbleAuthTokenInterface {

    /**
     * Should get the authenticated licence object described by the token
     */
    public function getAuthenticatedLicence(): AuthenticatedLicenceInterface;

    /**
     * Should get the user who own the token
     */
    public function getUser(): UserInterface;
}