<?php

namespace PebbleAuthClient\Services;

use PebbleAuthClient\Datatypes\AuthenticatedLicenceObject;
use PebbleAuthClient\Datatypes\PebbleTokenData;
use PebbleAuthClient\Datatypes\UserObject;
use PebbleAuthClient\Models\User;

class token {
    /**
     * Provide all token data and generate a new AuthenticatedLicenceObject instance.
     *
     * @param PebbleTokenData $tokenData
     * @return AuthenticatedLicenceObject
     */
    public function getLicenceObjectFromTokenData(PebbleTokenData $tokenData): AuthenticatedLicenceObject
    {
        $user = new User(new UserObject([
            'username' => $tokenData->sub,
            'roles' => $tokenData->roles,
            'level' => $tokenData->lv,
            'displayName' => $tokenData->name,
            'scopes' => $tokenData->scope ? explode(" ", $tokenData->scope) : []
        ]));

        return new AuthenticatedLicenceObject([
            'app' => $tokenData->client_id,
            'issuer' => $tokenData->iss,
            'tenant_id' => $tokenData->tid,
            'user' => $user
        ]);
    }

    /**
     * Generated a PebbleTokenData instance from a dict representation of the JWT and the token string.
     *
     * @param \stdClass $jwtPayload     all information stored in the token
     * @param string $token             original JWT string
     */
    public function getTokenDataFromJWTPayload(\stdClass $jwtPayload, string $token): PebbleTokenData
    {
        return new PebbleTokenData([
            "aud" => $jwtPayload->aud,
            "iss" => $jwtPayload->iss,
            "tid" => $jwtPayload->tid,
            "sub" => $jwtPayload->sub,
            "roles" => $jwtPayload->roles,
            "lv" => $jwtPayload->lv,
            "name" => $jwtPayload->name,
            "iat" => $jwtPayload->iat,
            "exp" => $jwtPayload->exp,
            "client_id" => $jwtPayload->client_id,
            "jti" => $jwtPayload->jti,
            "scope" => $jwtPayload->scope,
            "token" =>$token
        ]);
    }
}