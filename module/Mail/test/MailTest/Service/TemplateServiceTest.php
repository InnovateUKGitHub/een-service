<?php

namespace MailTest\Service;

use Common\Service\HttpService;
use Mail\Service\TemplateService;
use Zend\Http\Request;

/**
 * @covers \Mail\Service\TemplateService
 */
class TemplateServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var HttpService|\PHPUnit_Framework_MockObject_MockObject */
    private $serviceMock;
    /** @var TemplateService */
    private $service;

    public function testCreate()
    {
        $data = [
            'id'      => 'email-verification-opportunity',
            'subject' => 'Please verify your application',
            'macros'  => [],
        ];

        $fileName = __DIR__ . '/../../../template/' . $data['id'] . '.html';
        $body = file_get_contents($fileName);
        $params = [
            'uuid'                   => $data['id'],
            'subject'                => $data['subject'],
            'body'                   => $body,
            'macros'                 => $data['macros'],
            'open_tracking_enabled'  => true,
            'click_tracking_enabled' => true,
        ];

        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_POST, '/templates/email/', [], $params)
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->create($data)
        );
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testCreateException()
    {
        $this->service->create(['id' => 'unknown id']);
    }

    public function testUpdate()
    {
        $data = [
            'id'      => 'email-verification-opportunity',
            'subject' => 'Please verify your application',
            'macros'  => [],
        ];

        $fileName = __DIR__ . '/../../../template/' . $data['id'] . '.html';
        $body = file_get_contents($fileName);
        $params = [
            'uuid'                   => $data['id'],
            'subject'                => $data['subject'],
            'body'                   => $body,
            'macros'                 => $data['macros'],
            'open_tracking_enabled'  => true,
            'click_tracking_enabled' => true,
        ];

        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_POST, '/templates/email/1', [], $params)
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->update(1, $data)
        );
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testUpdateException()
    {
        $this->service->update(1, ['id' => 'unknown id']);
    }

    public function testDelete()
    {
        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_DELETE, '/templates/email/1')
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->delete(1)
        );
    }

    public function testGetList()
    {
        $this->serviceMock
            ->expects(self::once())
            ->method('execute')
            ->with(Request::METHOD_GET, '/templates/email')
            ->willReturn(['success' => true]);

        self::assertEquals(
            ['success' => true],
            $this->service->getList()
        );
    }

    protected function Setup()
    {
        $this->serviceMock = $this->createMock(HttpService::class);
        $this->service = new TemplateService($this->serviceMock);
    }
}
