<?php

namespace Contact\Controller;

use Mail\Service\MailService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class EmailController extends AbstractRestfulController
{
    /** @var MailService */
    private $service;

    /**
     * EmailController constructor.
     *
     * @param MailService $service
     */
    public function __construct(MailService $service)
    {
        $this->service = $service;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $data = $this->getInputFilter()->getValues();

        $params = [
            'recipients' => [
                [
                    'email'  => $data['email'],
                    'macros' => [
                        'email' => $data['email'],
                        'url'   => $data['url'],
                    ],
                ],
            ],
            '_links'     => [
                'email_template' => 'email-verification',
            ],
        ];

        return $this->service->send($params);
    }
}
