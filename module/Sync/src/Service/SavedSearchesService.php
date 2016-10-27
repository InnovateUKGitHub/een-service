<?php
namespace Sync\Service;

use Common\Constant\EEN;
use Common\Exception\ApplicationException;
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
        $savedSearch->Email__c = $this->getEmailAddress($user);
        $savedSearch->Content__c = $html;
        $object = new \SoapVar($savedSearch, SOAP_ENC_OBJECT, 'SavedSearches__c', $this->salesForce->getNamespace());

        $this->salesForce->action(
            new \SoapParam([$object], 'sObjects'),
            'create'
        );
    }

    /**
     * @param array $user
     *
     * @return string
     * @throws ApplicationException
     */
    private function getEmailAddress($user)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT c.Email1__c
FROM Contact c, c.Account a
WHERE Id = \'' . $user . '\'
';

        $result = $this->salesForce->query($query);

        if ($result['size'] !== 1) {
            throw new ApplicationException(['user' => 'User Id Invalid']);
        }

        return $result['records']['Email1__c'];
    }
}