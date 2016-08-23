<?php

namespace ConsoleTest\Service;

use Console\Validator\MerlinValidator;
use Zend\Log\Logger;

/**
 * @covers Console\Validator\MerlinValidator
 */
class MerlinValidatorTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|Logger $loggerMock */
    private $loggerMock;
    /** @var MerlinValidator $validator */
    private $validator;
    private $config;

    public function testCheckProfilesExists()
    {
        $this->validator = new MerlinValidator($this->loggerMock, $this->config);
        $merlinData = simplexml_load_string('<pod><profile></profile></pod>');
        $this->validator->checkProfilesExists($merlinData);
    }

    /**
     * @expectedException \Zend\Di\Exception\MissingPropertyException
     * @expectedExceptionMessage Merlin Data is missing the profiles
     */
    public function testCheckProfilesDoesNotExists()
    {
        $this->validator = new MerlinValidator($this->loggerMock, $this->config);
        $merlinData = simplexml_load_string('<not-profile></not-profile>');
        $this->validator->checkProfilesExists($merlinData);
    }

    public function testCheckProfileDataExistsWithRequiredValue()
    {
        $this->config = [
            'keyword' => [
                'commercials'  => [
                    'commercial' => [
                        'label' => '',
                    ],
                ],
                'technologies' => [
                    'technology' => [
                        'required' => false,
                        'label'   => '',
                    ],
                ],
            ],
        ];
        $this->validator = new MerlinValidator($this->loggerMock, $this->config);

        $xml = '
<pod>
<keyword>
    <commercials>
        <commercial>
            <label>Some Commercial</label>
        </commercial>
    </commercials>
    <technologies>
        <technology>
            <label>Some Technology</label>
        </technology>
    </technologies>
</keyword>
</pod>
';
        $merlinData = simplexml_load_string($xml);
        $this->validator->checkProfileDataExists($merlinData);
    }

    public function testCheckProfileDataExistsWithoutRequiredValue()
    {
        $this->config = [
            'keyword' => [
                'commercials'  => [
                    'commercial' => [
                        'label' => '',
                    ],
                ],
                'technologies' => [
                    'technology' => [
                        'required' => false,
                        'label'   => '',
                    ],
                ],
            ],
        ];
        $this->validator = new MerlinValidator($this->loggerMock, $this->config);

        $xml = '
<pod>
<keyword>
    <commercials>
        <commercial>
            <label>Hello</label>
        </commercial>
    </commercials>
    <technologies/>
</keyword>
</pod>
';
        $merlinData = simplexml_load_string($xml);
        $this->validator->checkProfileDataExists($merlinData);
    }

    /**
     * @expectedException \Zend\Di\Exception\MissingPropertyException
     * @expectedExceptionMessage Merlin Profile is missing keyword -> commercials
     */
    public function testCheckProfileDataExistsThrowException()
    {
        $this->config = [
            'keyword' => [
                'commercials'  => [
                    'commercial' => [
                        'label' => '',
                    ],
                ],
                'technologies' => [
                    'technology' => [
                        'required' => false,
                        'label'   => '',
                    ],
                ],
            ],
        ];
        $this->validator = new MerlinValidator($this->loggerMock, $this->config);

        $merlinData = simplexml_load_string('<pod><keyword></keyword></pod>');
        $this->validator->checkProfileDataExists($merlinData);
    }

    protected function Setup()
    {
        $this->config = [];
        $this->loggerMock = $this->createMock(Logger::class);
    }
}
