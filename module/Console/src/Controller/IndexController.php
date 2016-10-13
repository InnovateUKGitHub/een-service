<?php

namespace Console\Controller;

use Common\Constant\EEN;
use Console\Service\IndexService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class IndexController extends AbstractActionController
{
    /** @var IndexService $service */
    private $service;

    /**
     * @param IndexService $service
     */
    public function __construct(IndexService $service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function indexAction()
    {
        if (!($this->getRequest() instanceof Request)) {
            throw new BadMethodCallException('This is a console tool only');
        }

        // Create Indexes
        $this->service->createIndex(EEN::ES_INDEX_EVENT);
        $this->service->createIndex(EEN::ES_INDEX_OPPORTUNITY);

        // Update Settings
        $this->service->createSettings(EEN::ES_INDEX_EVENT);
        $this->service->createSettings(EEN::ES_INDEX_OPPORTUNITY);

        return "Index creation done.\n";
    }
}
