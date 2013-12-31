<?php
 /**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model\Struct;

use SMS\Exception,
    SMS\Model\Struct\SMS;

/**
 * Class SMSCollection
 * @package SMS\Model\Struct
 */
class SMSCollection extends \SplObjectStorage
{
    /**
     * @param object $object
     * @param null $data
     * @throws Exception\WrongArgument
     */
    public function attach($object, $data = null)
    {
        if (!$object instanceof SMS) {
            throw new Exception\WrongArgument();
        }
        parent::attach($object, $data);
    }

    /**
     * @param object $object
     * @throws Exception\WrongArgument
     */
    public function detach($object)
    {
        if (!$object instanceof SMS) {
            throw new Exception\WrongArgument();
        }
        parent::detach($object);
    }

    /**
     * @param object $object
     * @return bool
     * @throws Exception\WrongArgument
     */
    public function contains($object)
    {
        if (!$object instanceof SMS) {
            throw new Exception\WrongArgument();
        }
        parent::contains($object);
    }

    /**
     * @param \SplObjectStorage $object
     * @throws Exception\WrongArgument
     */
    public function addAll($object)
    {
        if (!$object instanceof SMSCollection) {
            throw new Exception\WrongArgument();
        }
        parent::addAll($object);
    }

    /**
     * @param \SplObjectStorage $object
     * @throws Exception\WrongArgument
     */
    public function removeAll($object)
    {
        if (!$object instanceof SMSCollection) {
            throw new Exception\WrongArgument();
        }
        parent::removeAll($object);
    }
}