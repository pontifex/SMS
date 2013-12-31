<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Struct;

use SMS\Model\Struct\SMSCollection;
use PHPUnit_Framework_TestCase;
use SMS\Model\Struct\SMS as SMSStruct;

/**
 * Class SMSCollectionTest
 * @package SMSTest\Model\Struct
 */
class SMSCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SMSCollection
     */
    protected $object;

    /**
     *
     */
    public function setup()
    {
        $this->object = new SMSCollection();
    }

    /**
     *
     */
    public function testAttachWithInvalidObjectShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\WrongArgument');

        $this->object->attach(new \stdClass());
    }

    /**
     *
     */
    public function testAttachWithValidObjectShouldReturnVoid()
    {
        $this->object->attach(new SMSStruct());
    }

    /**
     *
     */
    public function testDetachWithInvalidObjectShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\WrongArgument');

        $this->object->detach(new \stdClass());
    }

    /**
     *
     */
    public function testDetachWithValidObjectShouldReturnVoid()
    {
        $this->object->detach(new SMSStruct());
    }

    /**
     *
     */
    public function testContainsWithInvalidObjectShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\WrongArgument');

        $this->object->contains(new \stdClass());
    }

    /**
     *
     */
    public function testContainsWithValidObjectShouldReturnVoid()
    {
        $this->object->contains(new SMSStruct());
    }

    /**
     *
     */
    public function testAddAllWithInvalidObjectShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\WrongArgument');

        $this->object->addAll(new \stdClass());
    }

    /**
     *
     */
    public function testAddAllWithValidObjectShouldReturnVoid()
    {
        $this->object->addAll(new SMSCollection());
    }

    /**
     *
     */
    public function testRemoveAllWithInvalidObjectShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\WrongArgument');

        $this->object->removeAll(new \stdClass());
    }

    /**
     *
     */
    public function testRemoveAllWithValidObjectShouldReturnVoid()
    {
        $this->object->removeAll(new SMSCollection());
    }
}