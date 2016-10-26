<?php

namespace Common\Exception;

use Zend\Http\Response;

/**
 * @codeCoverageIgnore
 */
class ApplicationException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * ApplicationException constructor.
     *
     * @param array  $errors
     * @param string $message
     * @param int    $code
     */
    public function __construct($errors = [], $message = 'An error as occurred', $code = Response::STATUS_CODE_422)
    {
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}