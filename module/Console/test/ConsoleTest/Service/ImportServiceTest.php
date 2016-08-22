<?php

namespace ConsoleTest\Service;

use Console\Factory\Service\ImportServiceFactory;
use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Zend\Http\Request;
use Zend\Json\Server\Exception\HttpException;

/**
 * @covers Console\Service\ImportService
 */
class ImportServiceTest extends \PHPUnit_Framework_TestCase
{
    const SINCE = 1;
    const MONTH = 1;
    const PATH = 'tools/services/podv6/QueryService.svc/GetProfiles?';

    /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $indexServiceMock */
    private $indexServiceMock;
    /** @var \PHPUnit_Framework_MockObject_MockObject|HttpService $httpServiceMock */
    private $httpServiceMock;
    /** @var ImportService $service */
    private $service;

    protected function Setup()
    {
        $config = [
            ImportServiceFactory::SERVER    => 'een.ec.europa.eu',
            ImportServiceFactory::PORT      => '80',
            ImportService::PATH_GET_PROFILE => 'tools/services/podv6/QueryService.svc/GetProfiles?',
            ImportService::USERNAME         => '%%MERLIN_GLOBAL_USERNAME%%',
            ImportService::PASSWORD         => '%%MERLIN_GLOBAL_PASSWORD%%',
        ];

        $this->indexServiceMock = $this->createMock(IndexService::class);
        $this->httpServiceMock = $this->createMock(HttpService::class);

        $this->service = new ImportService(
            $this->httpServiceMock,
            $this->indexServiceMock,
            $config
        );
    }

    public function testDelete()
    {
        $now = new \DateTime();
        $return = [
            'hits' => [
                'hits' => [
                    [
                        '_source' => [
                            'id'          => 1,
                            'date_import' => $now->sub(new \DateInterval('P1D'))->format('Ymd'),
                            'date'        => '',
                        ],
                    ],
                    [
                        '_source' => [
                            'id'          => 2,
                            'date_import' => '',
                            'date'        => $now->sub(new \DateInterval('P13M'))->format('Ymd'),
                        ],
                    ],
                ],
            ],
        ];
        $this->indexServiceMock->expects(self::once())
            ->method('getAll')
            ->willReturn($return);

        $this->indexServiceMock->expects(self::once())
            ->method('delete')
            ->with([
                'body' => [
                    [
                        'delete' => [
                            '_index' => IndexService::ES_INDEX_OPPORTUNITY,
                            '_type'  => IndexService::ES_TYPE_OPPORTUNITY,
                            '_id'    => 1,
                        ],
                    ],
                    [
                        'delete' => [
                            '_index' => IndexService::ES_INDEX_OPPORTUNITY,
                            '_type'  => IndexService::ES_TYPE_OPPORTUNITY,
                            '_id'    => 2,
                        ],
                    ],
                ],
            ]);

        $this->service->delete(self::SINCE, $now);
    }

    public function testDeleteEmpty()
    {
        $now = new \DateTime();
        $return = [
            'hits' => [
                'hits' => [
                    [
                        '_source' => [
                            'id'          => 1,
                            'date_import' => $now->format('Ymd'),
                            'date'        => $now->format('Ymd'),
                        ],
                    ],
                    [
                        '_source' => [
                            'id'          => 2,
                            'date_import' => $now->format('Ymd'),
                            'date'        => $now->format('Ymd'),
                        ],
                    ],
                ],
            ],
        ];
        $this->indexServiceMock->expects(self::once())
            ->method('getAll')
            ->willReturn($return);

        $this->indexServiceMock->expects(self::never())
            ->method('delete');

        $this->service->delete(self::SINCE, $now);
    }

    public function testImport()
    {
        $merlinData = file_get_contents(__DIR__ . '/merlin-data.xml');

        $date = new \DateTime();

        $this->httpServiceMock->expects(self::once())
            ->method('setHttpMethod')
            ->with(Request::METHOD_GET);
        $this->httpServiceMock->expects(self::once())
            ->method('setPathToService')
            ->with(self::PATH);
        $this->httpServiceMock->expects(self::once())
            ->method('setQueryParams')
            ->with([
                'u'  => '%%MERLIN_GLOBAL_USERNAME%%',
                'p'  => '%%MERLIN_GLOBAL_PASSWORD%%',
                'sb' => $date->format('Ymd'),
                'sa' => $date->sub(new \DateInterval('P' . self::MONTH . 'M'))->format('Ymd'),
            ]);

        $this->httpServiceMock->expects(self::once())
            ->method('execute')
            ->willReturn($merlinData);

        $this->service->import(self::MONTH);
    }

    public function testIndexThrowException()
    {
        $merlinData = file_get_contents(__DIR__ . '/merlin-data.xml');

        $date = new \DateTime();
        $now = $date->format('Ymd');

        $this->httpServiceMock->expects(self::once())
            ->method('setHttpMethod')
            ->with(Request::METHOD_GET);
        $this->httpServiceMock->expects(self::once())
            ->method('setPathToService')
            ->with(self::PATH);
        $this->httpServiceMock->expects(self::once())
            ->method('setQueryParams')
            ->with([
                'u'  => '%%MERLIN_GLOBAL_USERNAME%%',
                'p'  => '%%MERLIN_GLOBAL_PASSWORD%%',
                'sb' => $now,
                'sa' => $date->sub(new \DateInterval('P' . self::MONTH . 'M'))->format('Ymd'),
            ]);

        $this->httpServiceMock->expects(self::once())
            ->method('execute')
            ->willReturn($merlinData);

        $this->indexServiceMock->expects(self::once())
            ->method('index')
            ->with([
                'id'                 => 'BOCN20160720001',
                'type'               => 'BO',
                'title'              => 'Chinese company offering aluminum sheet',
                'summary'            => 'The Summary',
                'description'        => 'The Description',
                'partner_expertise'  => 'Partner Expertise',
                'stage'              => '',
                'ipr'                => '',
                'ipr_comment'        => '',
                'country_code'       => 'CN',
                'country'            => 'China',
                'date'               => '2016-08-09T00:00:00',
                'deadline'           => '2017-08-09T00:00:00',
                'partnership_sought' => [
                    'Commercial agency agreement',
                ],
                'industries'         => [
                    'National or Regional R&D programme',
                ],
                'technologies'       => [
                    'Non-ferrous Metals',
                ],
                'commercials'        => [
                    'Mining of other non-ferrous metal ores',
                ],
                'markets'            => [
                    'Other Industrial Products (not elsewhere classified)',
                ],
                'eoi'                => true,
                'advantage'          => 'Advantage',
                'date_import'        => $now,
            ])
            ->willThrowException(new \Exception(''));

        $this->service->import(self::MONTH);
    }

    public function testGetDataThrowHttpException()
    {
        $this->httpServiceMock->expects(self::once())
            ->method('execute')
            ->willThrowException(new HttpException(''));

        self::assertNull($this->service->import(self::MONTH));
    }

    public function testGetDataThrowException()
    {
        $this->httpServiceMock->expects(self::once())
            ->method('execute')
            ->willThrowException(new \Exception(''));

        self::assertNull($this->service->import(self::MONTH));
    }
}
