<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model\Struct;

/**
 * Class Message
 * @package SMS\Model\Struct
 */
class Message
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->setContent($value);
    }

    /**
     * @return int
     */
    public function countLength()
    {
        return mb_strlen($this->content);
    }
}