<?php

namespace Common\Exception;

/**
 * @codeCoverageIgnore
 */
class ApplicationException extends \Exception
{
    /** @var array */
    private $messages = [];

    /**
     * ApplicationException constructor.
     *
     * @param array $messages
     */
    public function __construct($messages = [])
    {
        $this->messages = $messages;
        parent::__construct('An error as occurred', 520);
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;
    }
}