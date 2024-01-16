<?php

namespace PebbleAuthClient\Datatypes;

class UserObject {
    /**
     * Unique username. Most of the time, it is a email address
     */
    public string $username;

    /**
     * Name of the user used for display
     */
    public ?string $displayName;

    /**
     * From 1-6 : user level
     */
    public ?int $level;

    /**
     * Roles affected to the user
     */
    public ?array $roles;

    /**
     * Granted scopes onto the resource API
     */
    public ?array $scopes;

    public function __construct(array $values) {
        foreach ($values as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }
}