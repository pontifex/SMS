<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

return array(
    'service_manager' => array(
        'factories' => array(
            'SMS\Factory\SMSAPI' => 'SMS\Factory\SMSAPI',
            'SMS\Factory\SMSAPITest' => 'SMS\Factory\SMSAPITest',
            'SMS\Factory\OVH' => 'SMS\Factory\OVH',
        ),
    ),
);
