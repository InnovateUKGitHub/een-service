<?php
namespace Sync\Service;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Search\Service\QueryService;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

class SavedSearchesService
{
    /** @var QueryService */
    private $query;
    /** @var SalesForceService */
    private $renderer;
    /** @var PhpRenderer */
    private $salesForce;

    /**
     * SavedSearchesService constructor.
     *
     * @param QueryService      $query
     * @param SalesForceService $salesForce
     * @param PhpRenderer       $renderer
     */
    public function __construct(
        QueryService $query,
        SalesForceService $salesForce,
        PhpRenderer $renderer
    )
    {
        $this->query = $query;
        $this->salesForce = $salesForce;
        $this->renderer = $renderer;
    }

    /**
     * @param string $user
     */
    public function create($user)
    {
        $params = [
            'from'   => 0,
            'size'   => 10,
            'source' => ['id', 'title', 'summary'],
            'sort'   => ['date' => ['order' => 'desc']],
        ];

        $results = $this->query->search($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);

        $this->push($user, $results['results']);
    }

    /**
     * @param string $user
     * @param array  $profiles
     */
    private function push($user, $profiles)
    {
        $viewModel = new ViewModel(['profiles' => $profiles]);
        $viewModel->setTemplate('search-alert');
        $html = $this->renderer->render($viewModel);

        $savedSearch = new \stdClass();
        $savedSearch->Email__c = $user;
        $savedSearch->Content__c = $html;
        $objects = new \SoapVar($savedSearch, SOAP_ENC_OBJECT, 'SavedSearches__c', $this->salesForce->getNamespace());

        $this->salesForce->action(
            new \SoapParam([$objects], 'sObjects'),
            'create'
        );
    }
}