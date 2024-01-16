<?php

namespace PebbleAuthClient\Services;

use Firebase\JWT\JWT;
use PebbleAuthClient\Interfaces\PebbleAuthTokenInterface;
use PebbleAuthClient\Models\PebbleAuthToken;
use PebbleAuthClient\Services\Exceptions\AmbiguousTokenException;
use PebbleAuthClient\Services\Exceptions\EmptyTokenException;
use PebbleAuthClient\Services\Exceptions\InvalidClaimValueException;
use PebbleAuthClient\Services\Exceptions\InvalidTypException;

class auth {

    private key $keyService;

    private token $tokenService;

    public function __construct()
    {
        $this->keyService = new key();
        $this->tokenService = new token();
    }

    /**
     * Authenticate a provided token into and return a valid PebbleAuthToken object
     *
     * @param string $token Provided token
     * @param array|null $options Verifying options. Acceptable claims : audience, issuer. Resource API MUST control
     *                                  its audience name.
     *
     * @return PebbleAuthTokenInterface
     *
     * @throws InvalidTypException
     * @throws InvalidClaimValueException
     */
    public function auth(string $token, ?array $options = null): PebbleAuthTokenInterface
    {
        $keySet = $this->keyService->getJWKSet();

        $decoded = JWT::decode($token, $keySet);

        $this->verifyTyp($token);

        if (!$options) {
            $options = [];
        }

        // Control claims in body
        $claims = ['audience' => 'aud', 'issuer' => 'iss'];

        foreach ($claims as $label => $claim) {
            if (array_key_exists($claim, $options)) {
                $this->verifyClaimValue($decoded, $claim, $options[$label]);
            }
        }

        $tokenData = $this->tokenService->getTokenDataFromJWTPayload($decoded, $token);

        return new PebbleAuthToken($tokenData);
    }

    /**
     * Authenticate user using the HTTP Authorization header provided with the request
     *
     * The Authorization headers must be written according to the standard :
     * - Token content must start with "Bearer " string (ex : *Bearer full_token_string*)
     *
     * @param array $headers All provided headers (including Authorization) in an array. Headers can be formatted
     *                              according PSR7 getHeaders() recommendations.
     * @param array|null $options Verifying options. Acceptable claims : audience, issuer. Resource API MUST control
     *                              its audience name.
     *
     * @return PebbleAuthTokenInterface
     *
     * @throws AmbiguousTokenException
     * @throws EmptyTokenException
     */
    public function authFromHttpHeaders(array $headers, ?array $options = null): PebbleAuthTokenInterface
    {
        $headers = $this->lowerHeaderNames($headers);

        if (array_key_exists("authorization", $headers)) {
            $authorization = $headers["authorization"];

            if (is_array($authorization)) {
                if (count($authorization) > 1) {
                    throw new AmbiguousTokenException();
                }
                $authorization = $authorization[0];
            }

            $token = preg_replace("/^Bearer\s/", "", $authorization);
            return $this->auth($token, $options);
        }

        throw new EmptyTokenException();
    }

    /**
     * Check typ header claim of provided token.
     *
     * @param string $token Input token to verify
     * @param string $typ Expected typ value (default at+jwt)
     *
     * @return void
     *
     * @throws InvalidTypException
     */
    private function verifyTyp(string $token, string $typ = "at+jwt"): void
    {
        $headerEncoded = explode('.', $token)[0];
        $headerRaw = JWT::urlsafeB64Decode($headerEncoded);
        $headers = JWT::jsonDecode($headerRaw);

        if ($headers->typ !== $typ) {
            throw new InvalidTypException($typ);
        }
    }

    /**
     * Verify one claim on a provided decoded token. If decoded token claim value is an array, verified value must
     * exist once in the list.
     *
     * @param \stdClass $decodedToken Decoded token
     * @param string $claim Claim name that must be verified
     * @param mixed $value Expected value
     *
     * @return void
     *
     * @throws InvalidClaimValueException
     */
    private function verifyClaimValue(\stdClass $decodedToken, string $claim, mixed $value): void
    {
        if (is_array($decodedToken->$claim) && !in_array($value, $decodedToken->$claim)) {
            throw new InvalidClaimValueException($claim, $value);
        }
        else if ($decodedToken->$claim !== $value) {
            throw new InvalidClaimValueException($claim, $value);
        }
    }

    /**
     * Converted mixed case header names to lowercase header names.
     *
     * @param array $headers            Input headers
     *
     * @return array
     */
    private function lowerHeaderNames(array $headers): array
    {
        $lowerHeaders = [];

        foreach ($headers as $name => $values) {
            $lowerHeaders[strtolower($name)] = $values;
        }

        return $lowerHeaders;
    }
}