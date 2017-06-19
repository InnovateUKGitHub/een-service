<?php

return [
    'merlin'                   => [
        'server'       => 'een.ec.europa.eu',
        'path-profile' => '/tools/services/podv6/QueryService.svc/GetProfiles',
        'path-event'   => '/tools/services/podv6/QueryService.svc/GetEventsRest',
        'username'     => '%%MERLIN_GLOBAL_USERNAME%%',
        'password'     => '%%MERLIN_GLOBAL_PASSWORD%%',
    ],
    'merlin-profile-structure' => require __DIR__ . '/merlin-profile.structure.php',
    'merlin-event-structure'   => require __DIR__ . '/merlin-event.structure.php',
];
