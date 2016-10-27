<?php

namespace SyncTest\Service\Import;

use Common\Constant\EEN;
use Common\Service\SalesForceService;
use Search\Service\QueryService;
use Sync\Service\SavedSearchesService;
use Zend\View\Renderer\PhpRenderer;

/**
 * @covers \Sync\Service\SavedSearchesService
 */
class SavedSearchesServiceTest extends \PHPUnit_Framework_TestCase
{
    const USER = 1;

    /** @var \PHPUnit_Framework_MockObject_MockObject|QueryService */
    private $queryMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|SalesForceService */
    private $salesForceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|PhpRenderer */
    private $rendererMock;
    /** @var SavedSearchesService */
    private $service;

    public function testCreate()
    {
        $query = new \stdClass();
        $query->queryString = '
SELECT c.Email1__c
FROM Contact c, c.Account a
WHERE Id = \'' . self::USER . '\'
';
        $params = [
            'from'   => 0,
            'size'   => 10,
            'source' => ['id', 'title', 'summary'],
            'sort'   => ['date' => ['order' => 'desc']],
        ];
        $this->queryMock->expects(self::once())
            ->method('search')
            ->with($params, EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY)
            ->willReturn(['results' => []]);
        $this->rendererMock->expects(self::once())
            ->method('render')
            ->willReturn('Html');
        $this->salesForceMock->expects(self::at(0))
            ->method('query')
            ->with($query)
            ->willReturn(['size' => 1, 'records' => ['Email1__c' => 'email']]);

        $savedSearch = new \stdClass();
        $savedSearch->Email__c = 'email';
        $savedSearch->Content__c = 'Html';
        $object = new \SoapVar($savedSearch, SOAP_ENC_OBJECT, 'SavedSearches__c', 'namespace');

        $this->salesForceMock->expects(self::at(1))
            ->method('getNamespace')
            ->willReturn('namespace');
        $this->salesForceMock->expects(self::at(2))
            ->method('action')
            ->with(new \SoapParam([$object], 'sObjects'), 'create');

        $this->service->create(self::USER);
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testCreateUserNotFound()
    {
        $this->queryMock->expects(self::once())
            ->method('search')
            ->willReturn(['results' => []]);
        $this->salesForceMock->expects(self::at(0))
            ->method('query')
            ->willReturn(['size' => 0]);

        $this->service->create(self::USER);
    }

    protected function Setup()
    {
        $this->queryMock = $this->createMock(QueryService::class);
        $this->salesForceMock = $this->createMock(SalesForceService::class);
        $this->rendererMock = $this->createMock(PhpRenderer::class);

        $this->service = new SavedSearchesService(
            $this->queryMock,
            $this->salesForceMock,
            $this->rendererMock
        );
    }
}
