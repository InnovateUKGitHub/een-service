<?php

namespace Console\Controller;

use Console\Helper\Helper;
use Console\Service\DeleteService;
use Console\Service\GenerateService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class GenerateController extends AbstractActionController
{
    /** @var GenerateService */
    private $generateService;
    /** @var DeleteService */
    private $deleteService;

    /**
     * GenerateController constructor.
     *
     * @param GenerateService $generateService
     * @param DeleteService   $deleteService
     */
    public function __construct(GenerateService $generateService, DeleteService $deleteService)
    {
        $this->generateService = $generateService;
        $this->deleteService = $deleteService;
    }

    /**
     * @return array
     */
    public function generateAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = $this->params('index', 'all');
        $number = $this->params('number', 100);

        if (Helper::checkValidType($index) === false) {
            throw new InvalidArgumentException('The index enter is not valid');
        }
        if (is_numeric($number) === false) {
            throw new InvalidArgumentException('The number enter is not valid');
        }
        $this->generateService->generate($index, $number);

        return ['generate' => 'success'];
    }

    /**
     * @return array
     */
    public function deleteAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = $this->params('index', 'all');

        if (Helper::checkValidType($index) === false) {
            throw new InvalidArgumentException('The index enter is not valid');
        }

        $this->deleteService->delete($index);

        return ['delete' => 'success'];
    }
}
