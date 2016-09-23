<?php

namespace ConsoleTest\Service;

use Common\Constant\EEN;
use Console\Service\GenerateService;
use Console\Service\IndexService;

/**
 * @covers \Console\Service\GenerateService
 */
class GenerateServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateAll()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $serviceMock */
        $serviceMock = $this->createMock(IndexService::class);

        $faker = \Faker\Factory::create();

        $service = new GenerateService($serviceMock, $faker);

        $serviceMock
            ->expects(self::at(0))
            ->method('index')
            ->willReturn([]);
        $serviceMock
            ->expects(self::at(1))
            ->method('index')
            ->willReturn([]);

        $service->generate(GenerateService::ALL, 1);
    }

    public function testGenerateOpportunity()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $service */
        $serviceMock = $this->createMock(IndexService::class);

        $faker = \Faker\Factory::create();

        $service = new GenerateService($serviceMock, $faker);

        $serviceMock
            ->expects(self::exactly(1))
            ->method('index')
            ->willReturn([]);

        $service->generate(EEN::ES_INDEX_OPPORTUNITY, 1);
    }

    public function testGenerateEvent()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $serviceMock */
        $serviceMock = $this->createMock(IndexService::class);

        $faker = \Faker\Factory::create();

        $service = new GenerateService($serviceMock, $faker);

        $serviceMock
            ->expects(self::exactly(1))
            ->method('index')
            ->willReturn([]);

        $service->generate(EEN::ES_INDEX_EVENT, 1);
    }

    public function testGenerateNoIndexSpecified()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $serviceMock */
        $serviceMock = $this->createMock(IndexService::class);

        $faker = \Faker\Factory::create();

        $service = new GenerateService($serviceMock, $faker);

        $serviceMock
            ->expects(self::at(0))
            ->method('index')
            ->willReturn([]);
        $serviceMock
            ->expects(self::at(1))
            ->method('index')
            ->willReturn([]);

        $service->generate(null, 1);
    }

    public function testGenerateInvalidIndexSpecified()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|IndexService $serviceMock */
        $serviceMock = $this->createMock(IndexService::class);

        $faker = \Faker\Factory::create();

        $service = new GenerateService($serviceMock, $faker);

        $serviceMock
            ->expects(self::at(0))
            ->method('index')
            ->willReturn([]);
        $serviceMock
            ->expects(self::at(1))
            ->method('index')
            ->willReturn([]);

        $service->generate('InvalidIndex', 1);
    }
}
