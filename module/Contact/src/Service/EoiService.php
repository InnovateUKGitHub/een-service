<?php

namespace Contact\Service;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Search\Service\QueryService;
use ZF\ApiProblem\ApiProblemResponse;

class EoiService extends AbstractEntity
{
    const profileType = [
        'BO' => 'Business Offer',
        'BR' => 'Business Request',
        'TO' => 'Technology offer',
        'TR' => 'Technology Request',
        'RDR' => 'R&D request',
    ];

    /** @var QueryService */
    private $queryService;

    /**
     * @param SalesForceService $salesForce
     * @param QueryService      $queryService
     */
    public function __construct(SalesForceService $salesForce, QueryService $queryService)
    {
        $this->queryService = $queryService;
        parent::__construct($salesForce);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function create($data)
    {
        $eoi = new \stdClass();
        $eoi->Nature_of_interest__c = $data['interest'];
        $eoi->External_EEN_Partner__c = $data['account_id'];
        $eoi->Profile__c = $this->getProfile($data['profile_id']);

        $eoiId = $this->createEntity($eoi, 'Eoi__c');
        if ($eoiId instanceof ApiProblemResponse) {
            return $eoiId;
        }

        return ['id' => $eoiId];
    }

    private function getProfile($profileId)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT Id
FROM Profile__c
WHERE Profile_reference_number__c = \'' . $profileId . '\'
';

        $result = $this->salesForce->query($query);
        if ($result->size == 0) {
            return $this->createProfile($profileId);
        }

        return $result->records->Id;
    }

    private function createProfile($profileId)
    {
        $data = $this->queryService->getDocument($profileId, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY);

        $profile = new \stdClass();
        $profile->Profile_reference_number__c = $profileId;
        $profile->Profile_Title__c = $data['_source']['title'];
        $profile->Profile_Type__c = self::profileType[$data['_source']['type']];

        return $this->createEntity($profile, 'Profile__c');
    }
}
