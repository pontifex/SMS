<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Struct;

use PHPUnit_Framework_TestCase as TestCase;
use SMS\Model\Struct\NumberFrom;
use \SMS\Model\Struct\CountryPrefixConstantsInterface;

/**
 * Class NumberTest
 * @package SMSTest\Model\Struct
 */
class NumberTest extends TestCase implements CountryPrefixConstantsInterface
{
    /**
     * @var \SMS\Model\Struct\NumberAbstract
     */
    protected $object;

    /**
     *
     */
    public function setup()
    {
        $this->object = new NumberFrom(self::FRANCE, '123123123');
    }

    /**
     *
     */
    public function testSetCountryPrefixShouldSetValueOfFieldCountryPrefixAndReturnObject()
    {
        $actual = $this->object->setCountryPrefix(self::GREAT_BRITAIN);

        $this->assertEquals(self::GREAT_BRITAIN, $actual->getCountryPrefix());
    }

    /**
     *
     */
    public function testSetLocalNumberShouldSetValueOfFieldLocalNumberAndReturnObject()
    {
        $actual = $this->object->setLocalNumber('454454446');

        $this->assertEquals('454454446', $actual->getLocalNumber());
    }

    /**
     *
     */
    public function testGetNumberShouldReturnNumber()
    {
        $this->assertEquals('0033123123123', $this->object->getNumber());
    }
}
