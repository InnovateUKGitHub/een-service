<?php

namespace Sync\Controller;

use Sync\Service\SavedSearchesService;
use Zend\Console\Exception\BadMethodCallException;
use Zend\Console\Request;
use Zend\Mvc\Controller\AbstractActionController;

final class SavedSearchesController extends AbstractActionController
{
    /** @var SavedSearchesService */
    private $service;

    /**
     * SavedSearchesController constructor.
     *
     * @param SavedSearchesService $service
     */
    public function __construct(SavedSearchesService $service)
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

        $user = (string)$this->params('user');
        if (empty($user)) {
            throw new BadMethodCallException('Please specify the user to send the search results');
        }

        $this->service->create($user);

        return "Saved Searches transferred to SalesForce.\n";
    }
}
