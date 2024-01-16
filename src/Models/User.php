<?php

namespace PebbleAuthClient\Models;

use PebbleAuthClient\Datatypes\UserObject;
use PebbleAuthClient\Interfaces\UserInterface;

/**
 * This object represent an authenticated user.
 *
 * @param UserObject $userData
 */
class User extends UserObject implements UserInterface
{

    public function __construct(UserObject $userData)
    {
        parent::__construct([]);
        $this->roles = $userData->roles;
        $this->displayName = $userData->displayName;
        $this->level = $userData->level;
        $this->scopes = $userData->scopes;
        $this->username = $userData->username;
    }

    /**
     * Check if the user has the argument specified role.
     *
     * @param string $role          Role that must be checked
     */
    public function hasRole(string $role): bool
    {
        if ($this->roles) {
            return in_array($role, $this->roles);
        }
        return false;
    }

    /**
     * Check if the user is granted on the provided scopes.
     *
     * @param array $scopes         A list of scopes
     * @param string|null $policy   ONE = Return true if one scope is valid, ALL = Return true if all scope are valid.
     *                              Default is ONE
     * @return bool
     */
    public function hasScopes(array $scopes, string $policy = null): bool
    {
        $policy = $policy ?? 'ONE';

        if (!$this->scopes || !count($scopes)) {
            return false;
        }

        $count = 0;

        for ($i=0; $i<count($scopes); $i++) {

            // This line gets the unfiltered action : api:action.filter become api:action
            $unfilteredScope = preg_replace("/\.[\w\*]+$/m", "", $scopes[$i]);

            // This line gets the action name only
            $action = preg_replace("/^\w+:(\w+)\.?[\w\*]*/m", "$1", $scopes[$i]);

            for ($j = 0; $j<count($this->scopes); $j++) {
                $userScope = $this->scopes[$j];

                // If the user scope use a joker (*), it is replaced with the current action (joker means any action).
                if (preg_match("/:\*/m", $userScope)) {
                    $userScope = preg_replace("/:\*/", ":".$action, $userScope);
                }

                if ($scopes[$i] === $userScope || $unfilteredScope === $userScope) {
                    if (strtoupper($policy) === 'ONE') {
                        return true;
                    }
                    $count += 1;
                }
            }

        }

        return $count >= count($scopes);
    }
}