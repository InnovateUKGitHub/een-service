<?php

namespace Mail\Service;

use Common\Service\HttpService;
use Zend\Http\Request;
use Zend\Http\Response;
use ZF\ApiProblem\ApiProblem;
use ZF\ApiProblem\ApiProblemResponse;

class TemplateService
{
    /** @var HttpService */
    private $client;

    /**
     * TemplateService constructor.
     *
     * @param HttpService $client
     */
    public function __construct(HttpService $client)
    {
        $this->client = $client;
    }

    public function create($data)
    {
        $fileName = __DIR__ . '/../../template/' . $data['id'] . '.html';
        if (file_exists($fileName) === false) {
            return new ApiProblemResponse(
                new ApiProblem(
                    Response::STATUS_CODE_400,
                    'Template does not exist'
                )
            );
        }

        $body = file_get_contents($fileName);
        $params = [
            'uuid'                   => $data['id'],
            'subject'                => $data['subject'],
            'body'                   => $body,
            'macros'                 => $data['macros'],
            'open_tracking_enabled'  => true,
            'click_tracking_enabled' => true,
        ];

        return $this->client->execute(Request::METHOD_POST, '/templates/email/', [], $params);
    }

    public function update($id, $data)
    {
        $fileName = __DIR__ . '/../../template/' . $data['id'] . '.html';
        if (file_exists($fileName) === false) {
            return new ApiProblemResponse(
                new ApiProblem(
                    Response::STATUS_CODE_400,
                    'Template does not exist'
                )
            );
        }

        $body = file_get_contents($fileName);
        $params = [
            'uuid'                   => $data['id'],
            'subject'                => $data['subject'],
            'body'                   => $body,
            'macros'                 => $data['macros'],
            'open_tracking_enabled'  => true,
            'click_tracking_enabled' => true,
        ];

        return $this->client->execute(Request::METHOD_POST, '/templates/email/' . $id, [], $params);
    }

    public function delete($id)
    {
        $this->client->execute(Request::METHOD_DELETE, '/templates/email/' . $id);
        return ['success' => true];
    }

    public function getList()
    {
        return $this->client->execute(Request::METHOD_GET, '/templates/email');
    }
}
