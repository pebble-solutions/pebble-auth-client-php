<?php

namespace PebbleAuthClient\Interfaces;

interface UserInterface {

    /**
     * Should check if the user has the argument specified role.
     *
     * @param string $role          Role that must be checked
     */
    public function hasRole(string $role): bool;

    /**
     * Should check if the user is granted on the provided scopes.
     *
     * @param array $scopes         A list of scopes
     * @param string|null $policy   ONE = Return true if one scope is valid, ALL = Return true if all scope are valid.
     *                              Default is ONE
     * @return bool
     */
    public function hasScopes(array $scopes, string $policy = null): bool;
}