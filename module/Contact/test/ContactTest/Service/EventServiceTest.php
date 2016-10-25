<?php

namespace ContactTest\Service;

use Common\Service\SalesForceService;
use Contact\Service\EventService;
use Contact\Service\LeadService;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

/**
 * @covers \Contact\Service\EventService
 * @covers \Contact\Service\AbstractEntity
 */
class EventServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService $elasticSearchMock */
    private $serviceMock;
    /** @var LeadService $service */
    private $service;

    public function testCreate()
    {
        $data = [
            'contact' => 1,
            'event'   => 1,
            'dietary' => 'Food restriction',
        ];

        $attendee = new \stdClass();
        $attendee->Contact__c = $data['contact'];
        $attendee->Event__c = $data['event'];
        $attendee->Special_Dietary_Access_Requirements__c = $data['dietary'];

        $object = new \SoapVar($attendee, SOAP_ENC_OBJECT, 'Attendee__c', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at(0))
            ->method('getNamespace')
            ->willReturn('namespace');

        $this->serviceMock->expects(self::at(1))
            ->method('action')
            ->with($object, 'create')
            ->willReturn(1);

        self::assertEquals(['id' => 1], $this->service->create($data));
    }

    public function testCreateReturnError()
    {
        $data = [
            'contact' => 1,
            'event'   => 1,
            'dietary' => 'Food restriction',
        ];

        $attendee = new \stdClass();
        $attendee->Contact__c = $data['contact'];
        $attendee->Event__c = $data['event'];
        $attendee->Special_Dietary_Access_Requirements__c = $data['dietary'];

        $object = new \SoapVar($attendee, SOAP_ENC_OBJECT, 'Attendee__c', 'namespace');
        $object = new \SoapParam([$object], 'sObjects');

        $this->serviceMock
            ->expects(self::at(0))
            ->method('getNamespace')
            ->willReturn('namespace');

        $this->serviceMock->expects(self::at(1))
            ->method('action')
            ->with($object, 'create')
            ->willReturn(new ApiProblemResponse(new ApiProblem(400, 'error')));

        self::assertInstanceOf(ApiProblemResponse::class, $this->service->create($data));
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(SalesForceService::class);
        $this->service = new EventService($this->serviceMock);
    }
}
