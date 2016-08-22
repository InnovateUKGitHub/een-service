<?php

namespace ConsoleTest\Helper;

use Console\Helper\Helper;

/**
 * @covers Console\Helper\Helper
 */
class HelperTest extends \PHPUnit_Framework_TestCase
{
    public function testTypeValid()
    {
        self::assertTrue(Helper::checkValidType('opportunity'));
        self::assertTrue(Helper::checkValidType('event'));
        self::assertTrue(Helper::checkValidType('all'));
    }

    public function testTypeInvalid()
    {
        self::assertFalse(Helper::checkValidType('Opportunity'));
        self::assertFalse(Helper::checkValidType('Event'));
        self::assertFalse(Helper::checkValidType('All'));
        self::assertFalse(Helper::checkValidType('InvalidType'));
    }

    public function testMonthValid()
    {
        for ($month = 1; $month <= 12; $month++) {
            self::assertTrue(Helper::checkValidMonth($month));
        }
    }

    public function testMonthInvalid()
    {
        self::assertFalse(Helper::checkValidMonth(0));
        self::assertFalse(Helper::checkValidMonth(13));
        self::assertFalse(Helper::checkValidMonth('InvalidMonth'));
    }
}
