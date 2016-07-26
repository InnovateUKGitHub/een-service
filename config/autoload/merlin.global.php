<?php

return [
    'merlin' => [
        'server'           => 'een.ec.europa.eu',
        'port'             => '80',
        'path-get-profile' => 'tools/services/podv6/QueryService.svc/GetProfiles?',
        'content-type'     => 'application/xml',
        'username'         => '%%MERLIN_GLOBAL_USERNAME%%',
        'password'         => '%%MERLIN_GLOBAL_PASSWORD%%',
    ],
];
