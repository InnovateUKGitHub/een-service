<?php

namespace Sync\Controller;

use Sync\Helper\Helper;
use Sync\Service\GenerateService;
use Sync\Service\PurgeService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Exception\InvalidArgumentException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class GenerateController extends AbstractActionController
{
    /** @var GenerateService */
    private $generateService;
    /** @var PurgeService */
    private $purgeService;

    /**
     * GenerateController constructor.
     *
     * @param GenerateService $generateService
     * @param PurgeService    $purgeService
     */
    public function __construct(GenerateService $generateService, PurgeService $purgeService)
    {
        $this->generateService = $generateService;
        $this->purgeService = $purgeService;
    }

    /**
     * @return string
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

        return "$number documents generated on $index.\n";
    }

    /**
     * @return string
     */
    public function purgeAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        $index = $this->params('index', 'all');

        if (Helper::checkValidType($index) === false) {
            throw new InvalidArgumentException('The index enter is not valid');
        }

        $this->purgeService->purge($index);

        return "Purge done on $index.\n";
    }
}
