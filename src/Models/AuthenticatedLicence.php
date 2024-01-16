<?php

namespace PebbleAuthClient\Models;

use PebbleAuthClient\Datatypes\AuthenticatedLicenceObject;
use PebbleAuthClient\Interfaces\AuthenticatedLicenceInterface;
use \PebbleAuthClient\Interfaces\UserInterface;

/**
 * This object represent information stored in a licence owned by a user.
 *
 * @param $values AuthenticatedLicenceObject
 */
class AuthenticatedLicence extends AuthenticatedLicenceObject implements AuthenticatedLicenceInterface {

    public function __construct(AuthenticatedLicenceObject $licenceObject)
    {
        parent::__construct([]);
        $this->user = $licenceObject->user;
        $this->app = $licenceObject->app;
        $this->issuer = $licenceObject->issuer;
        $this->tenant_id = $licenceObject->tenant_id;
    }

    /**
     * Return the user who own the licence
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}