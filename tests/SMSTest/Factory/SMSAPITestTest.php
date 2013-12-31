<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Factory;

use SMS\Factory\SMSAPITest as SMSAPITestFactory;

/**
 * Class SMSAPITestTest
 * @package SMSTest\Factory
 */
class SMSAPITestTest extends AbstractFactory
{
    /**
     *
     */
    public function testCreateServiceShouldReturnInstanceOVHAdapter()
    {
        $this->getServiceManager()->setService('eventManager', $this->makeEventManagerMock());

        $factory = new SMSAPITestFactory();
        $expectedModelInstanceClass = 'SMS\Model\SMS';
        $expectedAdapterInstanceClass = 'SMS\Model\Adapter\SMSAPITest';

        $actual = $factory->createService($this->getServiceManager());

        $this->assertInstanceOf($expectedModelInstanceClass, $actual);
        $this->assertInstanceOf($expectedAdapterInstanceClass, $actual->getAdapter());
    }
}