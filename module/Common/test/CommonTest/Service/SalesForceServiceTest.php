<?php

namespace CommonTest\Service;

use Common\Service\SalesForceService;
use Zend\Soap\Client;

/**
 * @covers \Common\Service\SalesForceService
 */
class SalesForceServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Client */
    private $clientMock;
    /** @var SalesForceService */
    private $service;
    /** @var array */
    private $config;

    public function Setup()
    {
        $this->config = [
            SalesForceService::USERNAME     => 'test',
            SalesForceService::PASSWORD     => 'test',
            SalesForceService::TOKEN        => '-test',
            SalesForceService::SF_NAMESPACE => 'test',
        ];

        $this->clientMock = $this->createMock(Client::class);

        $this->service = new SalesForceService($this->clientMock, $this->config);
    }

    public function testGetUserInfo()
    {
        $result = new \stdClass();
        $result->infos = 'test';

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('getUserInfo', ['getUserInfo' => []])
            ->willReturn($result);

        $this->mockLogin();
        $this->service->login();
        self::assertEquals(
            ['infos' => 'test'],
            $this->service->getUserInfo()
        );
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testGetUserInfoThrowException()
    {
        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('getUserInfo', ['getUserInfo' => []])
            ->willThrowException(new \Exception());

        $this->mockLogin();
        $this->service->getUserInfo();
    }

    private function mockLogin()
    {
        $loginObject = new \stdClass();
        $loginObject->result = new \stdClass();
        $loginObject->result->serverUrl = 'server';
        $loginObject->result->sessionId = 'sessionId';

        $this->clientMock->expects(self::at(0))
            ->method('call')
            ->with('login',
                [
                    'login' => [
                        'username' => 'test',
                        'password' => 'test-test',
                    ],
                ])
            ->willReturn($loginObject);
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testLoginThrowException()
    {
        $this->clientMock->expects(self::at(0))
            ->method('call')
            ->with('login',
                [
                    'login' => [
                        'username' => 'test',
                        'password' => 'test-test',
                    ],
                ])
            ->willThrowException(new \Exception());

        $this->service->login();
    }

    public function testLogout()
    {
        $this->clientMock->expects(self::at(0))
            ->method('call')
            ->with('logout', ['logout' => []])
            ->willReturn('hello');

        $this->service->logout();
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testLogoutThrowException()
    {
        $this->clientMock->expects(self::at(0))
            ->method('call')
            ->with('logout', ['logout' => []])
            ->willThrowException(new \Exception());

        $this->service->logout();
    }

    public function testDescribeObject()
    {
        $data = new \stdClass();
        $data->sObjectType = 'test';
        $object = new \SoapVar($data, SOAP_ENC_OBJECT, 'sObjectType', $this->service->getNamespace());
        $object = new \SoapParam($object, 'sObjectType');

        $field = new \stdClass();
        $field->name = 'name';
        $field->type = 'type';
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->fields = [$field];

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('describeSObject', ['describeSObject' => $object])
            ->willReturn($result);

        $this->mockLogin();
        self::assertEquals([
            'name' => [
                'name' => 'name',
                'type' => 'type',
            ],
        ], $this->service->describesObject($data->sObjectType));
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testDescribeObjectThrowException()
    {
        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->willThrowException(new \Exception());

        $this->mockLogin();
        $this->service->describesObject([]);
    }

    public function testDelete()
    {
        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('delete', ['delete' => ['id']]);

        $this->mockLogin();
        $this->service->delete(['id']);
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testDeleteThrowException()
    {
        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->willThrowException(new \Exception());

        $this->mockLogin();
        $this->service->delete(['id']);
    }

    public function testActionCreate()
    {
        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = true;
        $result->result->id = 1;

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willReturn($result);

        $this->mockLogin();
        self::assertEquals(
            [
                'success' => true,
                'id'      => 1,
            ],
            $this->service->action($sObject, 'create')
        );
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testActionCreateSuccessFalseErrorsArray()
    {
        $error = new \stdClass();
        $error->fields = 'fields';
        $error->message = 'message';

        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = false;
        $result->result->errors = [$error];

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willReturn($result);

        $this->mockLogin();
        $this->service->action($sObject, 'create');
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testActionCreateSuccessFalseErrorsFieldsArray()
    {
        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = false;
        $result->result->errors = new \stdClass();
        $result->result->errors->fields = ['fields'];
        $result->result->errors->message = 'message';

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willReturn($result);

        $this->mockLogin();
        $this->service->action($sObject, 'create');
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testActionCreateSuccessFalseErrorsFields()
    {
        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = false;
        $result->result->errors = new \stdClass();
        $result->result->errors->fields = 'fields';
        $result->result->errors->message = 'message';

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willReturn($result);

        $this->mockLogin();
        $this->service->action($sObject, 'create');
    }

    /**
     * @expectedException \Common\Exception\ApplicationException
     */
    public function testActionCreateSuccessFalseErrorsWithoutField()
    {
        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = false;
        $result->result->errors = new \stdClass();
        $result->result->errors->message = 'message';

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willReturn($result);

        $this->mockLogin();
        $this->service->action($sObject, 'create');
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testActionThrowException()
    {
        $sObject = new \SoapParam('create', 'test');
        $result = new \stdClass();
        $result->result = new \stdClass();
        $result->result->success = false;
        $result->result->errors = new \stdClass();
        $result->result->errors->fields = 'fields';
        $result->result->errors->message = 'message';

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('create', ['create' => $sObject])
            ->willThrowException(new \Exception('message', 400));

        $this->mockLogin();
        $this->service->action($sObject, 'create');
    }

    public function testQuery()
    {
        $query = new \stdClass();
        $result = new \stdClass();
        $result->result = 1;

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('query', ['query' => $query])
            ->willReturn($result);

        $this->mockLogin();
        self::assertEquals(1, $this->service->query($query));
    }

    /**
     * @expectedException \Common\Exception\SoapException
     */
    public function testQueryThrowException()
    {
        $query = new \stdClass();

        $this->clientMock->expects(self::at(4))
            ->method('call')
            ->with('query', ['query' => $query])
            ->willThrowException(new \Exception('message', 400));

        $this->mockLogin();
        $this->service->query($query);
    }
}
