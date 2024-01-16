<?php

require_once ("../vendor/autoload.php");

$authService = new \PebbleAuthClient\Services\auth();

$pebbleAuthToken = $authService->auth("eyJhbGciOiJSUzI1NiIsImtpZCI6IjBfdl8ycWFYTFFrb1NIYjBRX0d5Ulp1OFZ1X2llVTVHT1UtN0JhUFY4M0UiLCJ0eXAiOiJhdCtqd3QifQ.eyJzdWIiOiJ0ZXN0QHBlYmJsZS5iemgiLCJpc3MiOiJNYWNCb29rLVByby1kZS1HdWlsbGF1bWUubG9jYWwiLCJhdWQiOlsiYXBpLnBlYmJsZS5zb2x1dGlvbnMvdjUvcHJvamVjdCIsImFwaS5wZWJibGUuc29sdXRpb25zL3Y1L2FjdGlvbiJdLCJ0aWQiOiIxamUzNGstZWQ0NWRzc3EtZWsiLCJyb2xlcyI6WyJtYW5hZ2VyIl0sImx2Ijo1LCJjbGllbnRfaWQiOiIwMUhLUTVHUkdHU1I3U0ZCQjFCTTRTUkY4NSIsInNjb3BlIjoicHJvamVjdDpyZWFkIHByb2plY3Q6Y3JlYXRlIHByb2plY3Q6d3JpdGUub3duIGFjdGlvbjpyZWFkIGFjdGlvbjp3cml0ZS5hc3NpZ25lZCBhY3Rpb246Y3JlYXRlIHByb2plY3Q6ZGVsZXRlIG1vbjpzY29wZSIsImlhdCI6MTcwNTM5OTYwNCwiZXhwIjoxNzA1NDAzMjA0LCJqdGkiOiJhYTA1NTYzYy1mZjcyLTRjMjAtOGQ0Yi0xNDEwNDJmYzEzYTAifQ.Z5k7L_scpPhaXE8eb3Tgt2LV6yJbELTuEhfr6cQHPVKtAW1Mk-GR68fQNPBvf-h2b99Ql6ulMVBmL5uL00GvAPK5bF-vskQEh8_8qd_VVJu1Y1ouCjEaULvDBRe4jLpnYpKZEETBcV7K1xVyAM_S1MF1V97INTZ1QEKLN2qZySgBvtGiUMj443rxHfDL_wsEE2OiZdXF-OgRhBM1ugE1cZPtmonu1uml2YCzAGQOnBXEVKhhn_9ux-c5z1va8kKwFThu4_CGFwcb8EvrFdAhUxzOKh1JmO61lRwp7rlfUx39OTvxWJh_BXTrOsx2tAxBm6LJ-HQakpj3b5taIQz7Cw",
[
    'audience' => "api.pebble.solutions/v5/activity"
]);
$user = $pebbleAuthToken->getUser();
$licence = $pebbleAuthToken->getAuthenticatedLicence();

var_dump($pebbleAuthToken);

var_dump($user);

var_dump($licence);

if ($user->hasRole("manager")) {
    echo "User is manager".PHP_EOL;
} else echo "User is not manager".PHP_EOL;

if ($user->hasRole("admin")) echo "User is admin".PHP_EOL;
else echo "User is not admin".PHP_EOL;

if ($user->hasScopes(['project:read'])) echo "User can read project".PHP_EOL;
else echo "User can't read project".PHP_EOL;

if ($user->hasScopes(['activity:write'])) echo "User can write activity".PHP_EOL;
else echo "User can't write activity".PHP_EOL;

if ($user->hasScopes(['activity:write', 'project:read'], "ONE")) echo "User can read project and/or write activity".PHP_EOL;
else echo "User as no access to read project or write activity".PHP_EOL;

if ($user->hasScopes(['activity:write', 'project:read'], "ALL")) echo "User can read project AND write activity".PHP_EOL;
else echo "User can't read project and can't write activity".PHP_EOL;

if ($user->hasScopes(['project:read.*'])) echo "User can read anything on project".PHP_EOL;
else echo "User as not full access on project".PHP_EOL;

if ($user->hasScopes(["action:write.assigned"])) echo "User can write on assigned action".PHP_EOL;
else echo "User can't write on assigned action".PHP_EOL;