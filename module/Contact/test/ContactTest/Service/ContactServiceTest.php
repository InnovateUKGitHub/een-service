<?php

namespace ContactTest\Service;

use Common\Service\SalesForceService;
use Contact\Service\ContactService;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * @covers \Contact\Service\ContactService
 * @covers \Contact\Service\AbstractEntity
 */
class ContactServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService $elasticSearchMock */
    private $serviceMock;
    /** @var ContactService $service */
    private $service;

    private function getData()
    {
        return [
            'email'          => 'email@email.com',
            'company_name'   => 'EEN',
            'company_phone'  => '01245789665',
            'company_number' => '012458',
            'website'        => 'google.com',
            'addressone'     => 'Street one',
            'addresstwo'     => 'Street tow',
            'postcode'       => 'BA1',
            'city'           => 'Bath',
            'firstname'      => 'firstname',
            'lastname'       => 'lastname',
            'phone'          => '03658965877',
            'contact_phone'  => '01254896325',
            'contact_email'  => 'email@test.com',
            'newsletter'     => true,
        ];
    }

    private function mockAccount($data, $action = 'create', $exception = false)
    {
        $object = $this->getAccount($data, $action == 'update' ? 1 : null);
        $object = new \SoapVar($object, SOAP_ENC_OBJECT, 'Account', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at(1))
            ->method('getNamespace')
            ->willReturn('namespace');

        if ($exception === true) {
            $this->serviceMock
                ->expects(self::at(2))
                ->method('action')
                ->with($object, $action)
                ->willReturn(new ApiProblemResponse(new ApiProblem(500, 'error')));

            return;
        }

        $this->serviceMock
            ->expects(self::at(2))
            ->method('action')
            ->with($object, $action)
            ->willReturn(1);
    }

    private function getAccount($data, $accountId = null)
    {
        $account = new \stdClass();
        if ($accountId !== null) {
            $account->Id = $accountId;
        }
        $account->Name = $data['company_name'];
        $account->Phone = $data['company_phone'];
        $account->Website = $data['website'];
        $account->Company_Registration_Number__c = $data['company_number'];

        $account->BillingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $account->BillingPostalCode = $data['postcode'];
        $account->BillingCity = $data['city'];

        $account->ShippingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $account->ShippingPostalCode = $data['postcode'];
        $account->ShippingCity = $data['city'];

        return $account;
    }

    private function mockContact($data, $action = 'create', $exception = false)
    {
        $object = $this->getContact($data, 1, $action == 'update' ? 1 : null);
        $object = new \SoapVar($object, SOAP_ENC_OBJECT, 'Contact', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at(3))
            ->method('getNamespace')
            ->willReturn('namespace');

        if ($exception === true) {
            $this->serviceMock
                ->expects(self::at(4))
                ->method('action')
                ->with($object, $action)
                ->willReturn(new ApiProblemResponse(new ApiProblem(500, 'error')));

            return;
        }

        $this->serviceMock
            ->expects(self::at(4))
            ->method('action')
            ->with($object, $action)
            ->willReturn(1);
    }

    private function getContact($data, $accountId, $contactId = null)
    {
        $contact = new \stdClass();
        if ($contactId !== null) {
            $contact->Id = $contactId;
        }
        $contact->Id = $contactId;
        $contact->FirstName = $data['firstname'];
        $contact->LastName = $data['lastname'];
        $contact->Phone = $data['phone'];
        $contact->MobilePhone = $data['contact_phone'];
        $contact->Email = $data['contact_email'];

        $contact->MailingStreet = $data['addressone'] . ' ' . $data['addresstwo'];
        $contact->MailingPostalCode = $data['postcode'];
        $contact->MailingCity = $data['city'];

        if (!empty($data['newsletter'])) {
            $contact->Email_Newsletter__c = true;
        }
        $contact->AccountId = $accountId;
        $contact->Contact_Status__c = 'Client';

        if ($contactId === null) {
            $contact->Email1__c = $data['email'];
        }

        return $contact;
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

        $this->serviceMock
            ->expects(self::at($at))
            ->method('query')
            ->with($query)
            ->willReturn($result);

        return $result;
    }

    public function testCreate()
    {
        $data = $this->getData();
        $this->mockAccount($data);
        $this->mockContact($data);
        $result = $this->mockGetUser($data, 5);

        self::assertEquals($result, $this->service->create($data));
    }

    public function testCreateFailAccount()
    {
        $data = $this->getData();
        $this->mockAccount($data, 'create', true);

        self::assertInstanceOf(ApiProblemResponse::class, $this->service->create($data));
    }

    public function testCreateFailContact()
    {
        $data = $this->getData();
        $this->mockAccount($data);
        $this->mockContact($data, 'create', true);

        self::assertInstanceOf(ApiProblemResponse::class, $this->service->create($data));
    }

    public function testUpdate()
    {
        $data = $this->getData();
        $this->mockGetUser($data, 0);
        $this->mockAccount($data, 'update');
        $this->mockContact($data, 'update');
        $result = $this->mockGetUser($data, 5);

        self::assertEquals($result, $this->service->create($data));
    }

    public function testDescribe()
    {
        $this->serviceMock
            ->expects(self::once())
            ->method('describesObject')
            ->with('Account')
            ->willReturn(1);

        self::assertEquals(1, $this->service->describe('Account'));
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(SalesForceService::class);
        $this->service = new ContactService($this->serviceMock);
    }
}
