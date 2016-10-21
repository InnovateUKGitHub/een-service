<?php

namespace Common\Exception;

use Zend\Http\Response;

/**
 * @codeCoverageIgnore
 */
class ApplicationException extends \Exception
{
    /**
     * ApplicationException constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        parent::__construct($message, Response::STATUS_CODE_422);
    }
}