<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Adapter;

use PHPUnit_Framework_TestCase,
    SMS\Model\Struct,
    Zend\ServiceManager\ServiceManager,
    SMSTest\Bootstrap,
    SMS\Model\Adapter,
    Zend\Http\Client,
    Zend\Http\Response,
    Zend\EventManager\EventManager;

/**
 * Class AbstractAdapter
 * @package SMSTest\Model\Adapter
 */
class AbstractAdapter extends PHPUnit_Framework_TestCase implements Struct\CountryPrefixConstantsInterface
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
        $this->getServiceManager()->setService('eventManager', $this->makeEventManagerMock());
    }

    /**
     * @return EventManager
     */
    protected function makeEventManagerMock() {
        $eventManagerMock = $this->getMockBuilder('\Zend\EventManager\EventManager')
            ->disableOriginalConstructor()
            ->getMock();

        return $eventManagerMock;
    }

    /**
     * @param string $sendRequestResult
     * @param null $makeClientResult
     * @return Adapter\SMSAPI
     */
    protected function makeSMSAPIMock($sendRequestResult = null, $makeClientResult = null)
    {
        $SMSAPIMock = $this->getMockBuilder('\SMS\Model\Adapter\SMSAPI')
            ->disableOriginalConstructor()
            ->setMethods(array('sendRequest', 'makeClient'))
            ->getMock();

        $SMSAPIMock->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue(
                $sendRequestResult
            ));

        $SMSAPIMock->expects($this->any())
            ->method('makeClient')
            ->will($this->returnValue(
                $makeClientResult
            ));

        return $SMSAPIMock;
    }

    /**
     * @return Client
     */
    protected function makeHttpClientMock()
    {
        $HTTPClientMock = $this->getMockBuilder('\Zend\Http\Client')
            ->disableOriginalConstructor()
            ->setMethods(array('send'))
            ->getMock();

        $HTTPClientMock->expects($this->any())
            ->method('send')
            ->will($this->returnValue(
                $this->makeHttpResponseMock()
            ));

        return $HTTPClientMock;
    }

    /**
     * @return Response
     */
    protected function makeHttpResponseMock()
    {
        $HTTPResponseMock = $this->getMockBuilder('\Zend\Http\Response')
            ->disableOriginalConstructor()
            ->getMock();

        return $HTTPResponseMock;
    }

    /**
     * @return Struct\SMS
     */
    protected function makeItem()
    {
        $item = new Struct\SMS();
        $item->setTo(new Struct\NumberTo(self::GERMANY, '456456456'));
        $item->setFrom(new Struct\NumberFrom(self::GERMANY, '123456789'));
        $item->setMessage(new Struct\Message('Message content!'));

        return $item;
    }

    /**
     * @param string $sendRequestResult
     * @param null $makeClientResult
     * @return Adapter\OVH
     */
    protected function makeOVHMock($sendRequestResult = '', $makeClientResult = null)
    {
        $OVHMock = $this->getMockBuilder('\SMS\Model\Adapter\OVH')
            ->disableOriginalConstructor()
            ->setMethods(array('sendRequest', 'makeClient'))
            ->getMock();

        $OVHMock->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue(
                $sendRequestResult
            ));

        $OVHMock->expects($this->any())
            ->method('makeClient')
            ->will($this->returnValue(
                $makeClientResult
            ));

        return $OVHMock;
    }
}
