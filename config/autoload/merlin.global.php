<?php

return [
    'merlin'                => [
        'server'           => 'een.ec.europa.eu',
        'port'             => '80',
        'path-get-profile' => 'tools/services/podv6/QueryService.svc/GetProfiles?',
        'path-get-event'   => 'tools/services/podv6/QueryService.svc/GetEventsRest?',
        'username'         => '%%MERLIN_GLOBAL_USERNAME%%',
        'password'         => '%%MERLIN_GLOBAL_PASSWORD%%',
    ],
    'merlin-profile-structure' => require __DIR__ . '/merlin-profile.structure.php',
    'merlin-event-structure' => require __DIR__ . '/merlin-event.structure.php',
];
