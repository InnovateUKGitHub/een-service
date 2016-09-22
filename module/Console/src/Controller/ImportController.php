<?php

namespace Console\Controller;

use Console\Helper\Helper;
use Console\Service\DeleteService;
use Console\Service\ImportService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class ImportController extends AbstractActionController
{
    /** @var ImportService */
    private $importService;
    /** @var DeleteService */
    private $deleteService;

    /**
     * GenerateController constructor.
     *
     * @param ImportService $importService
     * @param DeleteService $deleteService
     */
    public function __construct(ImportService $importService, DeleteService $deleteService)
    {
        $this->importService = $importService;
        $this->deleteService = $deleteService;
    }

    /**
     * @return array
     */
    public function importAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = (string)$this->params('index', 'opportunity');
        $month = (int)$this->params('month', 1);
        $type = (string)$this->params('type', 'u');

        if (Helper::checkValidMonth($month) === false) {
            throw new InvalidArgumentException('The month enter is not valid');
        }

        $this->importService->import($index, $month, $type);

        return ['success' => true];
    }

    /**
     * @return array
     */
    public function deleteAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = (string)$this->params('index', 'opportunity');
        $since = (int)$this->params('since', 12);

        if ($since <= 0) {
            throw new InvalidArgumentException('The month enter is not valid');
        }

        $this->deleteService->deleteOutOfDate($index, $since);

        return ['success' => true];
    }
}
