<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Factory;

use SMS\Factory\SMSAPI;

/**
 * Class SMSAPITest
 * @package SMSTest\Factory
 */
class SMSAPITest extends AbstractFactory
{
    /**
     *
     */
    public function testCreateServiceShouldReturnInstanceOVHAdapter()
    {
        $this->getServiceManager()->setService('eventManager', $this->makeEventManagerMock());

        $factory = new SMSAPI();
        $expectedModelInstanceClass = 'SMS\Model\SMS';
        $expectedAdapterInstanceClass = 'SMS\Model\Adapter\SMSAPI';

        $actual = $factory->createService($this->getServiceManager());

        $this->assertInstanceOf($expectedModelInstanceClass, $actual);
        $this->assertInstanceOf($expectedAdapterInstanceClass, $actual->getAdapter());
    }
}