<?php

namespace PebbleAuthClient\Services;

use Firebase\JWT\CachedKeySet;
use Phpfastcache\Config\ConfigurationOption;
use GuzzleHttp;
use Phpfastcache;

class key {

    /**
     * Return the location of remote pebble authenticator public keys set (JWKS) as defined in the sys global
     * environment variables
     * @var string
     */
    readonly string $JWKS_REMOTE_URI;

    /**
     * Contains the local folder for temporary store authentication credentials. Storing locally the credentials improves
     * server response. Default value : ./var/credentials/auth. This can be changed by exporting `PBL_CERTS_FOLDER` environment
     * variable.
     *
     * ```
     * export PBL_CERTS_FOLDER=./var/credentials/auth
     * ```
     *
     * @var string
     */
    readonly string $CERTS_FOLDER;

    /**
     * Contains the local path for the public keys set (JWKS)
     * @var string
     */
    readonly string $JWKS_LOCAL_PATH;

    /**
     * Duration in seconds after which Keys Set (JWKS) is considered as expired. All local copy of the keys must be destroyed
     * and the remote server will be requested to create the new copy. Default value : 86400 sec (one day). This can be changed
     * by exporting `PBL_JWKS_EXP_TIME` environment variable.
     *
     * ```
     * export PBL_JWKS_EXP_TIME=3600
     * ```
     *
     * @var int
     */
    readonly int $JWKS_EXP_TIME;

    public function __construct()
    {
        $this->JWKS_REMOTE_URI = getenv("PBL_JWKS_REMOTE_URI");

        $env_certs = getenv('PBL_CERTS_FOLDER');
        $this->CERTS_FOLDER = $env_certs;

        $this->JWKS_LOCAL_PATH = $this->CERTS_FOLDER . "/jwks.json";

        $env_exp = getenv('PBL_JWKS_EXP_TIME');
        $this->JWKS_EXP_TIME = $env_exp ? (int) $env_exp : 86400;
    }

    /**
     * Return all the JWK currently stored in jwks.json file or in the process memory.
     */
    public function getJWKSet(): CachedKeySet
    {
        // The URI for the JWKS you wish to cache the results from
        $jwksUri = $this->JWKS_REMOTE_URI;

        // Create an HTTP client (can be any PSR-7 compatible HTTP client)
        $httpClient = new GuzzleHttp\Client();

        // Create an HTTP request factory (can be any PSR-17 compatible HTTP request factory)
        $httpFactory = new GuzzleHttp\Psr7\HttpFactory();

        Phpfastcache\CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => $this->CERTS_FOLDER,
        ]));

        // Create a cache item pool (can be any PSR-6 compatible cache item pool)
        $cacheItemPool = Phpfastcache\CacheManager::getInstance('files');

        $keySet = new CachedKeySet(
            $jwksUri,
            $httpClient,
            $httpFactory,
            $cacheItemPool,
            $this->JWKS_EXP_TIME, // $expiresAfter int seconds to set the JWKS to expire
            true  // $rateLimit    true to enable rate limit of 10 RPS on lookup of invalid keys
        );

        return $keySet;
    }
}