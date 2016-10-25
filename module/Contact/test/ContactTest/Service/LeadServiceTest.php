<?php

namespace ContactTest\Service;

use Common\Service\SalesForceService;
use Contact\Service\LeadService;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * @covers \Contact\Service\LeadService
 * @covers \Contact\Service\AbstractEntity
 */
class LeadServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService $elasticSearchMock */
    private $serviceMock;
    /** @var LeadService $service */
    private $service;

    public function testCreate()
    {
        $data = $this->getData();
        $this->mockLead($data);
        $result = $this->mockGetUser($data, 3);

        self::assertEquals((array)$result->records, $this->service->create($data));
    }

    public function testCreateAlreadyExists()
    {
        $data = $this->getData();
        $result = $this->mockGetUser($data, 0);

        self::assertEquals((array)$result->records, $this->service->create($data));
    }

    public function testCreateError()
    {
        $data = $this->getData();
        $this->mockLead($data, true);

        self::assertInstanceOf(ApiProblemResponse::class, $this->service->create($data));
    }

    private function getData()
    {
        return [
            'email' => 'email@email.com',
        ];
    }

    private function mockLead($data, $exception = false)
    {
        $lead = new \stdClass();
        $lead->Email1__c = $data['email'];
        $lead->LastName = 'Lead';

        $object = new \SoapVar($lead, SOAP_ENC_OBJECT, 'Contact', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');


        $this->serviceMock
            ->expects(self::at(1))
            ->method('getNamespace')
            ->willReturn('namespace');
        if ($exception === true) {
            $this->serviceMock
                ->expects(self::at(2))
                ->method('action')
                ->with($object, 'create')
                ->willReturn(new ApiProblemResponse(new ApiProblem(400, 'error')));
            return;
        }
        $this->serviceMock
            ->expects(self::at(2))
            ->method('action')
            ->with($object, 'create')
            ->willReturn(1);
    }

    private function mockGetUser($data, $at)
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT c.Id, c.Email, c.Contact_Status__c, c.FirstName, c.LastName, c.Phone, c.MobilePhone,
c.Email_Newsletter__c, c.MailingStreet, c.MailingPostalCode, c.MailingCity,
a.Id, a.Name, a.Phone, a.Website, a.Company_Registration_Number__c
FROM Contact c, c.Account a
WHERE Email1__c = \'' . $data['email'] . '\'
';

        $result = new \stdClass();
        $result->records = new \stdClass();
        $result->records->Id = 1;
        $result->records->Account = new \stdClass();
        $result->records->Account->Id = 1;
        $result->size = 1;

        $this->serviceMock
            ->expects(self::at($at))
            ->method('query')
            ->with($query)
            ->willReturn($result);

        return $result;
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(SalesForceService::class);
        $this->service = new LeadService($this->serviceMock);
    }
}
