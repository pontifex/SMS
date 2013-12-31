<?php
 /**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model\Struct;

use SMS\Model\Struct\Message,
    SMS\Model\Struct\NumberTo,
    SMS\Model\Struct\NumberFrom;

/**
 * Class SMS
 * @package SMS\Model\Struct
 */
class SMS
{
    /**
     * @var NumberFrom
     */
    protected $from;

    /**
     * @var NumberTo
     */
    protected $to;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @param Message $message
     * @return SMS
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param NumberFrom $from
     * @return SMS
     */
    public function setFrom(NumberFrom $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @return NumberFrom
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param NumberTo $to
     * @return SMS
     */
    public function setTo(NumberTo $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return NumberTo
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->initStruct();
    }

    /**
     *
     */
    protected function initStruct()
    {
        $this->setMessage(new Message(''));
        $this->setFrom(new NumberFrom());
        $this->setTo(new NumberTo());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'from' => $this->getFrom()->getLocalNumber(),
            'to' => $this->getTo()->getLocalNumber(),
            'message' => $this->getMessage()->getContent(),
        );
    }

    /**
     * @param array $data
     */
    public function fromArray(array $data)
    {
        if (array_key_exists('from', $data)) {
            $this->getFrom()->setLocalNumber($data['from']);
        }

        if (array_key_exists('to', $data)) {
            $this->getTo()->setLocalNumber($data['to']);
        }

        if (array_key_exists('message', $data)) {
            $this->getMessage()->setContent($data['message']);
        }
    }
}