<?php
/**
     * @author lucas.wawrzyniak
     * @copyright Copyright (c) 2013 Lucas Wawrzyniak
     * @licence New BSD License
     */

namespace SMS\Model\Struct;

/**
 * Class Result
 * @package SMS\Model\Struct
 */
class Result
{
    /**
     * @var array
     */
    protected $smsIds;

    /**
     * @var int
     */
    protected $creditLeft;

    /**
     * @var int
     */
    protected $creditUsed;

    /**
     * @return array
     */
    public function getSmsIds()
    {
        return $this->smsIds;
    }

    /**
     * @param $smsIds array
     * @return $this
     */
    public function setSmsIds(array $smsIds)
    {
        $this->smsIds = $smsIds;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreditLeft()
    {
        return $this->creditLeft;
    }

    /**
     * @param $creditLeft int
     * @return $this
     */
    public function setCreditLeft($creditLeft)
    {
        $this->creditLeft = $creditLeft;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreditUsed()
    {
        return $this->creditUsed;
    }

    /**
     * @param $creditUsed
     * @return $this
     */
    public function setCreditUsed($creditUsed)
    {
        $this->creditUsed = $creditUsed;

        return $this;
    }

    /**
     * @param array $smsIds
     * @param int $creditLeft
     * @param int $creditUsed
     */
    public function __construct(array $smsIds = array(), $creditLeft = null, $creditUsed = null)
    {
        $this->set($smsIds, $creditLeft, $creditUsed);
    }

    /**
     * @param Result $result
     * @return $this
     */
    public function add(Result $result)
    {
        $this->set(
            $this->smsIds + $result->getSmsIds(),
            $result->creditLeft,
            $this->creditUsed + $result->creditUsed
        );

        return $this;
    }

    /**
     * @param array $smsIds
     * @param int $creditLeft
     * @param int $creditUsed
     * @return $this
     */
    protected function set(array $smsIds = array(), $creditLeft = null, $creditUsed = 0)
    {
        $this->setSmsIds($smsIds);

        if (null !== $creditLeft) {
            $this->setCreditLeft($creditLeft);
        }

        $this->setCreditUsed($creditUsed);

        return $this;
    }
}

