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
            throw new InvalidArgumentException('The month enter is not valid');
        }

        $this->importService->import($month);

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

        $since = (int)$this->params('since', 12);

        if ($since <= 0) {
            throw new InvalidArgumentException('The month enter is not valid');
        }

        $this->importService->delete($since, new \DateTime());

        return ['success' => true];
    }
}
