<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

return [
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
    'curl-opt'     => [
        'max-connection'   => 3,
        'fresh-connection' => true,
        'timeout'          => 300,
    ],
];
