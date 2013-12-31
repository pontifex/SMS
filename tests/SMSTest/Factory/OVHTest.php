<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Factory;

use SMS\Factory\OVH;

/**
 * Class OVHTest
 * @package SMSTest\Factory
 */
class OVHTest extends AbstractFactory
{
    /**
     *
     */
    public function testCreateServiceShouldReturnInstanceOVHAdapter()
    {
        $this->getServiceManager()->setService('eventManager', $this->makeEventManagerMock());

        $factory = new OVH();
        $expectedModelInstanceClass = 'SMS\Model\SMS';
        $expectedAdapterInstanceClass = 'SMS\Model\Adapter\OVH';

        $actual = $factory->createService($this->getServiceManager());

        $this->assertInstanceOf($expectedModelInstanceClass, $actual);
        $this->assertInstanceOf($expectedAdapterInstanceClass, $actual->getAdapter());
    }
}