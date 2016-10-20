<?php

namespace Sync\Controller;

use Sync\Helper\Helper;
use Sync\Service\DeleteService;
use Sync\Service\ImportService;
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
     * @return string
     */
    public function importAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = (string)$this->params('index', 'opportunity');
        $month = (int)$this->params('month', 1);

        if (Helper::checkValidMonth($month) === false) {
            throw new InvalidArgumentException('The month enter is not valid');
        }

        $this->importService->import($index, $month);

        return "Import $index for month $month done.\n";
    }

    /**
     * @return string
     */
    public function deleteAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = (string)$this->params('index', 'opportunity');

        $this->deleteService->deleteOutOfDate($index);

        return "Delete of old date on $index done.\n";
    }
}
