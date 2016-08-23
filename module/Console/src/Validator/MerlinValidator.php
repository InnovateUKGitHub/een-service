<?php
namespace Console\Validator;

use Zend\Di\Exception\MissingPropertyException;
use Zend\Log\Logger;

class MerlinValidator
{
    /** @var array */
    private $structure;
    /** @var Logger */
    private $logger;

    /**
     * MerlinValidator constructor.
     *
     * @param Logger $logger
     * @param array  $structure
     */
    public function __construct(Logger $logger, $structure)
    {
        $this->logger = $logger;
        $this->structure = $structure;
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
     * @param array             $structure
     * @param string            $path
     */
    public function checkProfileDataExists(\SimpleXMLElement $data, $structure = null, $path = '')
    {
        if ($structure === null) {
            $structure = $this->structure;
        }

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
                $this->checkProfileDataExists($data->{$key}, $value, $path === '' ? $key : $path . ' -> ' . $key);
            }
        }
    }
}