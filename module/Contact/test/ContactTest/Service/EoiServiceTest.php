<?php

namespace ContactTest\Service;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Contact\Service\EoiService;
use Search\Service\QueryService;

/**
 * @covers \Contact\Service\EoiService
 * @covers \Contact\Service\AbstractEntity
 */
class EoiServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService $elasticSearchMock */
    private $serviceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService $queryServiceMock */
    private $queryServiceMock;
    /** @var EoiService $service */
    private $service;

    public function testCreateAndGetProfile()
    {
        $data = $this->getData();
        $this->mockGetProfile($data);

        $this->mockEoi($data);

        self::assertEquals(['id' => 1], $this->service->create($data));
    }

    public function testCreateAndCreateProfile()
    {
        $data = $this->getData();
        $this->mockCreateProfile($data);

        $this->mockEoi($data, 3);

        self::assertEquals(['id' => 1], $this->service->create($data));
    }

    private function getData()
    {
        return [
            'interest'   => 'I\'m interested by this project',
            'account_id' => '1',
            'profile_id' => '2',
        ];
    }

    private function mockEoi($data, $at = 1)
    {
        $eoi = new \stdClass();
        $eoi->Nature_of_interest__c = $data['interest'];
        $eoi->External_EEN_Partner__c = $data['account_id'];
        $eoi->Profile__c = 1;

        $object = new \SoapVar($eoi, SOAP_ENC_OBJECT, 'Eoi__c', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at($at))
            ->method('getNamespace')
            ->willReturn('namespace');

        $this->serviceMock
            ->expects(self::at($at + 1))
            ->method('action')
            ->with($object, 'create')
            ->willReturn(['id' => 1]);
    }

    private function mockGetProfile($data)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT Id
FROM Profile__c
WHERE Profile_reference_number__c = \'' . $data['profile_id'] . '\'
';

        $result = [
            'size'    => 1,
            'records' => [
                'Id' => 1,
            ],
        ];

        $this->serviceMock
            ->expects(self::at(0))
            ->method('query')
            ->with($query)
            ->willReturn($result);

        return $result;
    }

    private function mockCreateProfile($data)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT Id
FROM Profile__c
WHERE Profile_reference_number__c = \'' . $data['profile_id'] . '\'
';

        $result = [
            'size' => 0,
        ];

        $this->serviceMock
            ->expects(self::at(0))
            ->method('query')
            ->with($query)
            ->willReturn($result);

        $this->queryServiceMock->expects(self::once())
            ->method('getDocument')
            ->with($data['profile_id'], EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn([
                '_source' => [
                    'title' => 'title',
                    'type'  => 'BO',
                ],
            ]);

        $profile = new \stdClass();
        $profile->Profile_reference_number__c = $data['profile_id'];
        $profile->Name = 'title';
        $profile->Profile_Type__c = 'Business Offer';

        $object = new \SoapVar($profile, SOAP_ENC_OBJECT, 'Profile__c', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at(1))
            ->method('getNamespace')
            ->willReturn('namespace');
        $this->serviceMock
            ->expects(self::at(2))
            ->method('action')
            ->with($object, 'create')
            ->willReturn(['id' => 1]);

        return $result;
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(SalesForceService::class);
        $this->queryServiceMock = $this->createMock(QueryService::class);
        $this->service = new EoiService($this->serviceMock, $this->queryServiceMock);
    }
}
