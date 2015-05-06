<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Factory;

use PHPUnit_Framework_TestCase,
    SMSTest\Bootstrap,
    Zend\ServiceManager\ServiceManager;

/**
 * Class AbstractFactory
 * @package SMSTest\Factory
 */
class AbstractFactory extends PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param $serviceManager
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     *
     */
    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function makeEventManagerMock() {
        $eventManagerMock = $this->getMockBuilder('\Zend\EventManager\EventManager')
            ->disableOriginalConstructor()
            ->getMock();

        return $eventManagerMock;
    }
}
