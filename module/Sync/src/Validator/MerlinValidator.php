<?php
namespace Sync\Validator;

use Zend\Di\Exception\MissingPropertyException;
use Zend\Log\Logger;

class MerlinValidator
{
    /** @var Logger */
    private $logger;

    /**
     * MerlinValidator constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \SimpleXMLElement $data
     */
    public function checkProfilesExists(\SimpleXMLElement $data)
    {
        if (isset($data->{'profile'}) === false || count($data->{'profile'}) === 0) {
            $this->logger->debug('Merlin Data is missing the profiles');
            throw new MissingPropertyException('Merlin Data is missing the profiles');
        }
    }

    /**
     * @param \SimpleXMLElement $data
     */
    public function checkEventsExists(\SimpleXMLElement $data)
    {
        if (isset($data->{'event'}) === false || count($data->{'event'}) === 0) {
            $this->logger->debug('Merlin Data is missing the events');
            throw new MissingPropertyException('Merlin Data is missing the events');
        }
    }

    /**
     * @param \SimpleXMLElement $data
     * @param array             $structure
     * @param string            $path
     */
    public function checkDataExists(\SimpleXMLElement $data, $structure, $path = '')
    {
        foreach ($structure as $key => $value) {
            if (isset($value['required']) && $value['required'] === false) {
                unset($value['required']);
                if (isset($data->{$key}) === false) {
                    continue;
                }
            }
            if (isset($data->{$key}) === false) {
                $this->logger->debug('Merlin Profile is missing ' . ($path === '' ? $key : $path . ' -> ' . $key));
                throw new MissingPropertyException('Merlin Profile is missing ' . ($path === '' ? $key : $path . ' -> ' . $key));
            }
            if (is_array($value)) {
                $this->checkDataExists($data->{$key}, $value, $path === '' ? $key : $path . ' -> ' . $key);
            }
        }
    }
}