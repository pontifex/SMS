<?php
/**
 * Adapter for http://www.smsapi.pl/ in test mode.
 * Shares credentials with regular SMSAPI adapter.
 *
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model\Adapter;

use SMS\Model\Struct\SMS;

/**
 * Class SMSAPITest
 * @package SMS\Model\Adapter
 */
class SMSAPITest extends SMSAPI
{
    /**
     * @param SMS $item
     * @return string
     */
    protected function prepareUrl(SMS $item)
    {
        return parent::prepareUrl($item) . '&test=1';
    }
}
