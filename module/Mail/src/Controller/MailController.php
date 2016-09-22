<?php

namespace Mail\Controller;

use Mail\Service\MailService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class MailController extends AbstractRestfulController
{
    /** @var MailService */
    private $service;

    /**
     * CountryController constructor.
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
        $params = $this->getInputFilter()->getValues();

        return $this->service->send($params);
    }
}
