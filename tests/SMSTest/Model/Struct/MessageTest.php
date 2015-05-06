<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Struct;

use PHPUnit_Framework_TestCase as TestCase;
use SMS\Model\Struct\Message;

/**
 * Class MessageTest
 * @package SMSTest\Model\Struct
 */
class MessageTest extends TestCase
{
    /**
     * @var Message
     */
    protected $object;

    /**
     *
     */
    public function setup()
    {
        $this->object = new Message('This is my message!');
    }

    /**
     * Asserts countLength method return 0 when content is empty
     */
    public function testCountLengthShouldReturnZeroWhenContentIsEmpty()
    {
        $this->object->setContent(null);
        $this->assertEquals(0, $this->object->countLength());
    }

    /**
     * Asserts countLength method return correct value when content is not empty
     */
    public function testCountLengthShouldReturnCorrectValueWhenContentIsNotEmpty()
    {
        $this->assertEquals(19, $this->object->countLength());
    }
}
