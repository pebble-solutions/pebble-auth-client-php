<?php

namespace PebbleAuthClient\Models;

use PebbleAuthClient\Datatypes\PebbleTokenData;
use PebbleAuthClient\Interfaces\PebbleAuthTokenInterface;
use PebbleAuthClient\Interfaces\UserInterface;
use PebbleAuthClient\Interfaces\AuthenticatedLicenceInterface;
use PebbleAuthClient\Services\token;

class PebbleAuthToken extends PebbleTokenData implements PebbleAuthTokenInterface {

    public function __construct(PebbleTokenData $tokenData)
    {
        parent::__construct([]);
        $this->token = $tokenData->token;
        $this->aud = $tokenData->aud;
        $this->client_id = $tokenData->client_id;
        $this->exp = $tokenData->exp;
        $this->iat = $tokenData->iat;
        $this->iss = $tokenData->iss;
        $this->jti = $tokenData->jti;
        $this->lv = $tokenData->lv;
        $this->name = $tokenData->name;
        $this->roles = $tokenData->roles;
        $this->scope = $tokenData->scope;
        $this->sub = $tokenData->sub;
        $this->tid = $tokenData->tid;
    }

    /**
     * Get the user who own the token
     */
    public function getUser(): UserInterface
    {
        return $this->getAuthenticatedLicence()->getUser();
    }

    /**
     * Get the authenticated licence object described by the token
     */
    public function getAuthenticatedLicence(): AuthenticatedLicenceInterface
    {
        return new AuthenticatedLicence((new token())->getLicenceObjectFromTokenData($this));
    }
}