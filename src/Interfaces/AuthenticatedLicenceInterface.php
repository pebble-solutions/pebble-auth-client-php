<?php

namespace PebbleAuthClient\Interfaces;

interface AuthenticatedLicenceInterface {

    /**
     * Should return the user who own the licence
     */
    public function getUser(): UserInterface;
}