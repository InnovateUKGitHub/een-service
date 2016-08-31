<?php

namespace ConsoleTest\Factory\Service;

use Console\Factory\Service\ImportServiceFactory;
use Console\Service\HttpService;
use Console\Service\ImportService;
use Console\Service\IndexService;
use Console\Validator\MerlinValidator;
use Zend\Log\Logger;
use Zend\ServiceManager\ServiceManager;

/**
 * @covers Console\Factory\Service\ImportServiceFactory
 */
class ImportServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [
                ImportServiceFactory::SERVER    => 'een.ec.europa.eu',
                ImportServiceFactory::PORT      => '80',
                ImportService::PATH_GET_PROFILE => 'tools/services/podv6/QueryService.svc/GetProfiles?',
                ImportService::USERNAME         => '%%MERLIN_GLOBAL_USERNAME%%',
                ImportService::PASSWORD         => '%%MERLIN_GLOBAL_PASSWORD%%',
            ],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(0))
            ->method('get')
            ->with(HttpService::class)
            ->willReturn($this->createMock(HttpService::class));
        $serviceManager
            ->expects(self::at(1))
            ->method('get')
            ->with(IndexService::class)
            ->willReturn($this->createMock(IndexService::class));
        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);
        $serviceManager
            ->expects(self::at(3))
            ->method('get')
            ->with(MerlinValidator::class)
            ->willReturn($this->createMock(MerlinValidator::class));
        $serviceManager
            ->expects(self::at(4))
            ->method('get')
            ->with(Logger::class)
            ->willReturn($this->createMock(Logger::class));

        self::assertInstanceOf(
            ImportService::class,
            (new ImportServiceFactory())->__invoke($serviceManager)
        );
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the merlin information
     */
    public function testFactoryNoMerlinConfig()
    {
        $config = [];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the server
     */
    public function testFactoryNoMerlinConfigServer()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the port
     */
    public function testFactoryNoMerlinConfigPort()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [
                ImportServiceFactory::SERVER => 'een.ec.europa.eu',
            ],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the username
     */
    public function testFactoryNoMerlinConfigUsername()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [
                ImportServiceFactory::SERVER => 'een.ec.europa.eu',
                ImportServiceFactory::PORT   => '80',
            ],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the password
     */
    public function testFactoryNoMerlinConfigPassword()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [
                ImportServiceFactory::SERVER => 'een.ec.europa.eu',
                ImportServiceFactory::PORT   => '80',
                ImportService::USERNAME      => '%%MERLIN_GLOBAL_USERNAME%%',
            ],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }

    /**
     * @expectedException \Zend\Http\Exception\InvalidArgumentException
     * @expectedExceptionMessage The config file is incorrect. Please specify the path_get_profile
     */
    public function testFactoryNoMerlinConfigPath()
    {
        $config = [
            ImportServiceFactory::CONFIG_MERLIN => [
                ImportServiceFactory::SERVER => 'een.ec.europa.eu',
                ImportServiceFactory::PORT   => '80',
                ImportService::USERNAME      => '%%MERLIN_GLOBAL_USERNAME%%',
                ImportService::PASSWORD      => '%%MERLIN_GLOBAL_PASSWORD%%',
            ],
        ];

        /* @var \PHPUnit_Framework_MockObject_MockObject|ServiceManager $serviceManager */
        $serviceManager = $this->createMock(ServiceManager::class);

        $serviceManager
            ->expects(self::at(2))
            ->method('get')
            ->with(ImportServiceFactory::CONFIG_SERVICE)
            ->willReturn($config);

        (new ImportServiceFactory())->__invoke($serviceManager);
    }
}
