<?php

namespace SyncTest\Service\Opportunity;

use Common\Constant\EEN;
use Sync\Service\IndexService;
use Sync\Service\Opportunity\OpportunityMerlin;
use Sync\Service\Opportunity\OpportunityService;
use Sync\Validator\MerlinValidator;
use Zend\Escaper\Escaper;

/**
 * @covers \Sync\Service\Opportunity\OpportunityService
 */
class OpportunityServiceTest extends \PHPUnit_Framework_TestCase
{
    const MONTH = 1;
    const STRUCTURE = [];

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexMock */
    private $indexMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|OpportunityMerlin $merlinMock */
    private $merlinMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|MerlinValidator $validatorMock */
    private $validatorMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|\HTMLPurifier $purifierMock */
    private $purifierMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|Escaper $escaperMock */
    private $escaperMock;
    /** @var OpportunityService $service */
    private $service;

    public function testDeleteEmpty()
    {
        $now = new \DateTime();

        $this->indexMock->expects(self::at(0))
            ->method('getOutOfDateData')
            ->willReturn(['hits' => ['hits' => []]]);
        $this->indexMock->expects(self::at(1))
            ->method('getOutOfDateData')
            ->willReturn(['hits' => ['hits' => []]]);

        $this->indexMock->expects(self::never())
            ->method('delete');
        $this->service->delete($now);
    }

    public function testDelete()
    {
        $now = new \DateTime();

        $this->indexMock->expects(self::at(0))
            ->method('getOutOfDateData')
            ->with(EEN::ES_INDEX_OPPORTUNITY, EEN::ES_TYPE_OPPORTUNITY, $now->format(EEN::DATE_FORMAT_IMPORT))
            ->willReturn(['hits' => ['hits' => [['_id' => '1']]]]);
        $this->indexMock->expects(self::at(1))
            ->method('getOutOfDateData')
            ->with(EEN::ES_INDEX_COUNTRY, EEN::ES_TYPE_COUNTRY, $now->format(EEN::DATE_FORMAT_IMPORT))
            ->willReturn(['hits' => ['hits' => [['_id' => '1']]]]);

        $this->indexMock->expects(self::once())
            ->method('delete')
            ->with([
                'body' => [
                    [
                        'delete' => [
                            '_index' => EEN::ES_INDEX_OPPORTUNITY,
                            '_type'  => EEN::ES_TYPE_OPPORTUNITY,
                            '_id'    => 1,
                        ],
                    ],
                    [
                        'delete' => [
                            '_index' => EEN::ES_INDEX_COUNTRY,
                            '_type'  => EEN::ES_TYPE_COUNTRY,
                            '_id'    => 1,
                        ],
                    ],
                ],
            ]);
        $this->service->delete($now);
    }

    public function testImport()
    {
        $xml = file_get_contents(__DIR__ . '/merlin-opportunity.xml');
        $results = simplexml_load_string($xml);

        $this->merlinMock->expects(self::once())
            ->method('getList')
            ->with(self::MONTH)
            ->willReturn($results);

        $this->indexMock->expects(self::at(0))
            ->method('createIndex')
            ->with(EEN::ES_INDEX_OPPORTUNITY);
        $this->indexMock->expects(self::at(1))
            ->method('createIndex')
            ->with(EEN::ES_INDEX_COUNTRY);
        $this->indexMock->expects(self::at(2))
            ->method('index');
        $this->indexMock->expects(self::at(3))
            ->method('index');

        $this->validatorMock->expects(self::at(0))
            ->method('checkProfilesExists')
            ->with($results);
        $this->validatorMock->expects(self::at(1))
            ->method('checkDataExists')
            ->with($results->{'profile'}, self::STRUCTURE);

        $this->escaperMock->expects(self::any())
            ->method('escapeHtml')
            ->willReturn('The Summary with list:
            - A list
            - Of awesome
            - Feature
            ');

        $this->service->import(self::MONTH);
    }

    protected function Setup()
    {
        $this->indexMock = $this->createMock(IndexService::class);
        $this->merlinMock = $this->createMock(OpportunityMerlin::class);
        $this->validatorMock = $this->createMock(MerlinValidator::class);
        $this->purifierMock = $this->createMock(\HTMLPurifier::class);
        $this->escaperMock = $this->createMock(Escaper::class);

        $this->service = new OpportunityService(
            $this->indexMock,
            $this->merlinMock,
            $this->validatorMock,
            $this->purifierMock,
            $this->escaperMock,
            self::STRUCTURE
        );
    }
}
