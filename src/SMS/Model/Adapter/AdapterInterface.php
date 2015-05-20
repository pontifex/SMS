<?php
/**
     * @author lucas.wawrzyniak
     * @copyright Copyright (c) 2013 Lucas Wawrzyniak
     * @licence New BSD License
     */

namespace SMS\Model\Adapter;

use SMS\Exception,
    SMS\Model\Struct;

/**
 * Class AdapterInterface
 * @package SMS\Model\Adapter
 */
interface AdapterInterface
{
    public function preSend(Struct\SMS $item);
    public function send(Struct\SMS $item);
    public function postSend(Struct\SMS $item, Struct\Result $result);
    public function errorOnSend(Struct\SMS $item, Exception\AdapterInternalError $exception);
    public function isItemValid(Struct\SMS $item);
}
