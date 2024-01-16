Quickstart
==========

Introduction
------------

This library offer a client for authenticate user and licence management written in PHP compatible with many PHP API
Resource Server.

Installation
------------

Requirements
~~~~~~~~~~~~

The following procedures explains the installation of the following packages :

- PHP 8 or higher
- composer

Install with composer
~~~~~~~~~~~~~~~~~~~~~

If your project use composer, simply add the following.

::

    composer require pebble-solutions/pebbleauthclient

Usage
-----

Configuration
~~~~~~~~~~~~~

Before you can work with the library, you must define a system environment variable with the URI of the public Json Web
Key Set (remote JWKS file).

This file will be requested and store **temporary** on your API Server. Your server should be able to write on
*./var/credentials/auth/jwks.json* . If the file does not exist, it will be created.

**If you start your server directly from a terminal, run this command on your terminal before starting your server :**

::

    export PBL_JWKS_REMOTE_URI=https://SERVER_URI/path/jwks.json

**If you start your server within a Docker container, you should add this line to your Dockefile :**

.. code:: Dockerfile

    ENV PBL_JWKS_REMOTE_URI=https://SERVER_URI/path/jwks.json

**Other configurations**

You can add more configuration by defining some more environment variables on your system. These configurations have
values by default that works for most of the cases.

+-------------------------+--------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Environment variable    | Default                  | Description                                                                                                                                                                              |
+=========================+==========================+==========================================================================================================================================================================================+
| ``PBL_JWKS_REMOTE_URI`` | *Unset*                  | **MANDATORY** URI of the remote jwks.json file. This file contains all active public keys to decode token.                                                                               |
+-------------------------+--------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``PBL_CERTS_FOLDER``    | ./var/credentials/auth   | Local folder for temporary store authentication credentials. Storing locally the credentials improves server response.                                                                   |
+-------------------------+--------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``PBL_JWKS_EXP_TIME``   | 86400                    | Duration in seconds after which Keys Set (JWKS) is considered as expired. All local copy of the keys must be destroyed and the remote server will be requested to create the new copy.   |
+-------------------------+--------------------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

Test keys pair
~~~~~~~~~~~~~~

.. warning::
    These key files are not secured and must be used FOR TESTING PURPOSE ONLY on a local development environment !

**JWKS URI (for PBL_JWKS_REMOTE_URI environment variable)**

https://storage.googleapis.com/pebble-public-cdn/test_auth/jwks_test.json

**Public and private keys used to sign a token**

https://storage.googleapis.com/pebble-public-cdn/test_auth/public_test.pem

https://storage.googleapis.com/pebble-public-cdn/test_auth/private_test.pem

Authenticate with token string
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: PHP

    $authService = new \PebbleAuthClient\Services\auth();

    try {
        $pebbleAuthToken = $authService->auth("a.valid.token");
        $user = $pebbleAuthToken->getUser();
        $licence = $pebbleAuthToken->getAuthenticatedLicence();

        var_dump($pebbleAuthToken);
        var_dump($user);
        var_dump($licence);
    }
    catch (Exception $e) {
        echo "Error : ".$e->getMessage();
    }

Authenticate with HTTP Authorization header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. note::

    This example shows one way to serverside authenticate a user with the Authorization header. The important thing is
    to communicate an array to ``authFromHttpHeaders()`` function with a valid Authorization key value.

.. code:: PHP

    /**
     * This class is an example of a custom authenticator for symfony.
     */
    class TokenAuthenticator extends AbstractAuthenticator
    {
        /** ... */

        public function authenticate(Request $request): Passport
        {
            $authService = new \PebbleAuthClient\Services\auth();

            try {
                $pebbleAuthToken = $authService->authFromHttpHeaders($request->headers->all());
                $user = $pebbleAuthToken->getUser();
                $licence = $pebbleAuthToken->getAuthenticatedLicence();

                var_dump($pebbleAuthToken);
                var_dump($user);
                var_dump($licence);
            }
            catch (Exception $e) {
                throw $e;
            }

            // implement your own logic to get the user identifier
            $userIdentifier = /** ... */;

            return new SelfValidatingPassport(new UserBadge($userIdentifier));
        }

        /** ... */
    }

.. note::

    ``$headers`` parameter in ``authFromHttpHeaders()`` method is compliant with `PSR-7 standard recommendation <https://www.php-fig.org/psr/psr-7/>`_.
    Theses values must be considered as valid :

    ..

    Key to string relation :

    .. code:: PHP

        [
            "authorization" => "my.valid.token"
        ]

    Key to array of strings relation :

    .. code:: PHP

        [
            "authorization" => [
                "my.valid.token"
        ]

    However, even if PSR-7 accept multiple values for the same header name, the following will cause an AmbiguousToken
    error. It is not allowed to provide multiple token throw the authorization header.

    .. code:: PHP

        [
            "authorization" => [
                "my.first.token",
                "my.second.token",
        ]

Check the audience
~~~~~~~~~~~~~~~~~~

Audience identifies the recipients that the token is intended for. Each resource server MUST be identified by its
audience name and the authorization process MUST check that this audience exists in the token.

.. warning::
    By default, audience is not checked by the authentication process. It is the responsibility of the resource server
    to communicate its audience name in order to only accept token that has been generated for the this specific
    resource server.

To check the audience, add an ``$options`` array to the ``auth()`` or ``authFromHttpHeaders()`` functions.

.. code:: PHP

    $authService = new \PebbleAuthClient\Services\auth();

    // Check that the provided token has a valid audience for api.pebble.solutions/v5/my-resource
    $auth_token = $authService->auth("----my.valid.token----", [
        'audience' => "api.pebble.solutions/v5/my-resource"
    ]);

    // Check that token communicate through authorization header has a valid audience
    // for api.pebble.solutions/v5/my-resource
    $auth_token = $authService->authFromHttpHeaders(headers, [
        'audience' => "api.pebble.solutions/v5/my-resource"
    ]);
