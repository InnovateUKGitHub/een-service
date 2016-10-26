<?php

namespace Common\Exception;

/**
 * @codeCoverageIgnore
 */
class SoapException extends ApplicationException
{
    public function __construct(\Exception $e, $request, $response)
    {
        $errors = [
            'exception' => $e->getMessage(),
            'request'   => $request,
            'response'  => $response,
        ];
        parent::__construct($errors, $e->getMessage(), $e->getCode());
    }
}