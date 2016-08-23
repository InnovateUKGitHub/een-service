<?php

return [
    'service_manager' => array(
        'factories' => array(
            Zend\Log\Logger::class => function () {
                $log = new Zend\Log\Logger();
                $writer = new Zend\Log\Writer\Stream('logs/service.log');
                $log->addWriter($writer);

                return $log;
            },
        ),
    ),
];
