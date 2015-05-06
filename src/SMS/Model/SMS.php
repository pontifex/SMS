<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model;

use SMS\Exception,
    SMS\Model\Adapter\AdapterAbstract,
    SMS\Model\Struct;

/**
 * Class SMS
 * @package SMS\Model
 */
class SMS
{
    /**
     * @var AdapterAbstract
     */
    protected $adapter;

    /**
     * @var Struct\Result
     */
    protected $result;

    /**
     * @param AdapterAbstract $adapter
     * @return $this
     */
    public function setAdapter(AdapterAbstract $adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return AdapterAbstract
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return Struct\Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param AdapterAbstract $adapter
     */
    public function __construct(AdapterAbstract $adapter)
    {
        $this->setAdapter($adapter);
        $this->result = new Struct\Result();
    }

    /**
     * @param Struct\SMSCollection $itemColl
     * @return Struct\Result
     * @throws Exception\EmptyCollection
     */
    public function send(Struct\SMSCollection $itemColl)
    {
        if ($this->isCollectionEmpty($itemColl)) {
            throw new Exception\EmptyCollection();
        }

        /** @var Struct\SMS $item */
        foreach ($itemColl as $item) {
            $this->sendItem($item);
        }

        return $this->getResult();
    }

    /**
     * @param Struct\SMSCollection $itemColl
     * @return bool
     */
    protected function isCollectionEmpty(Struct\SMSCollection $itemColl)
    {
        return (0 === $itemColl->count()) ? true : false;
    }

    /**
     * @param Struct\SMS $item
     * @throws \Exception|Exception\AdapterInternalError
     */
    protected function sendItem(Struct\SMS $item)
    {
        try {
            $this->validateItem($item);

            $this->getAdapter()->preSend($item);
            $result = $this->getAdapter()->send($item);
            $this->getAdapter()->postSend($item, $result);

            $this->getResult()->add($result);
        } catch (Exception\AdapterInternalError $e) {
            $this->getAdapter()->errorOnSend($item, $e);
            throw $e;
        }
    }

    /**
     * @param Struct\SMS $item
     * @throws Exception\InvalidItem
     */
    protected function validateItem(Struct\SMS $item)
    {
        if (!$this->getAdapter()->isItemValid($item)) {
            throw new Exception\InvalidItem();
        }

        return;
    }
}
