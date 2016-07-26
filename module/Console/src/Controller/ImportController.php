<?php

namespace Console\Controller;

use Console\Helper\Helper;
use Console\Service\ImportService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;

class ImportController extends AbstractActionController
{
    /** @var ImportService */
    private $importService;

    /**
     * GenerateController constructor.
     *
     * @param ImportService $importService
     */
    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function importAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $type = $this->params('type', 'bo');

        if (Helper::checkValidProfileType($type) === false) {
            throw new InvalidArgumentException('The index enter is not valid');
        }

        $this->importService->import($type);

        return ['import' => 'success'];
    }
}
