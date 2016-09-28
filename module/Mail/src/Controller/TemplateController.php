<?php

namespace Mail\Controller;

use Mail\Service\TemplateService;
use Zend\InputFilter\InputFilter;
use Zend\Mvc\Controller\AbstractRestfulController;

/**
 * @method InputFilter getInputFilter()
 */
final class TemplateController extends AbstractRestfulController
{
    /** @var TemplateService */
    private $service;

    /**
     * CountryController constructor.
     *
     * @param TemplateService $service
     */
    public function __construct(TemplateService $service)
    {
        $this->service = $service;
    }

    public function create($data)
    {
        $params = $this->getInputFilter()->getValues();

        return $this->service->create($params);
    }

    public function update($id, $data)
    {
        $params = $this->getInputFilter()->getValues();

        return $this->service->update($id, $params);
    }

    public function delete($id)
    {
        return $this->service->delete($id);
    }

    public function getList()
    {
        return $this->service->getList();
    }
}
