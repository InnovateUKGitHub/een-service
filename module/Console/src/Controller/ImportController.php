<?php

namespace Console\Controller;

use Console\Helper\Helper;
use Console\Service\ImportService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class ImportController extends AbstractActionController
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

    /**
     * @return array
     */
    public function importAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $month = (int)$this->params('month', 1);

        if (Helper::checkValidMonth($month) === false) {
            throw new InvalidArgumentException('The index enter is not valid');
        }

        $this->importService->import($month);

        return ['success' => true];
    }
}
