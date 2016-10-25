<?php

namespace Contact\Controller;

use Contact\Service\EoiService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class EoiController extends AbstractRestfulController
{
    /** @var EoiService */
    private $service;

    /**
     * ContactController constructor.
     *
     * @param EoiService $service
     */
    public function __construct(EoiService $service)
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

        return $this->service->create($params);
    }
}
