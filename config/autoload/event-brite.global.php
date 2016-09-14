<?php

return [
    'event-brite' => [
        'server'                => 'www.eventbriteapi.com/v3',
        'scheme'                => 'https',
        'secret'                => '%%EVENT_BRITE_SECRET%%',
        'oauth-path'            => '/oauth/authorize',
        'oauth-token'           => '%%EVENT_BRITE_TOKEN%%',
        'oauth-token-anonymous' => '***REMOVED***',
        'events-path'           => '/organizers/7829726093/events/',
    ],
];
