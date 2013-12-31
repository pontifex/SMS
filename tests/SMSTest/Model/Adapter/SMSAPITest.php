<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Adapter;

use SMS\Model\Adapter,
    SMS\Model\Struct,
    SMSTest\Bootstrap;

/**
 * Class SMSAPITest
 * @package SMSTest\Model\Adapter
 */
class SMSAPITest extends AbstractAdapter
{
    /**
     *
     */
    public function testMakeClient()
    {
        $config = array(
            'smsapi' => array(
                'url' => 'URL',
                'username' => 'USERNAME',
                'password' => 'PASSWORD'
            )
        );

        $this->getServiceManager()->setService('Config', $config);

        $obj = new Adapter\SMSAPI($this->getServiceManager());
        $makeClient = Bootstrap::getMethod('SMS\Model\Adapter\SMSAPI', 'makeClient');

        $actual = $makeClient->invokeArgs(
            $obj,
            array(
                $this->makeItem()
            )
        );

        $this->assertEquals('GET', $actual->getMethod());
        $this->assertEquals(
            'username=USERNAME&password=PASSWORD&to=0049456456456&message=Message+content%21',
            $actual->getUri()->getQuery()
        );
    }

    /**
     *
     */
    public function testSendRequest()
    {
        $SMSAPIMock = $this->getMockBuilder('\SMS\Model\Adapter\SMSAPI')
            ->disableOriginalConstructor()
            ->setMethods(array('makeClient'))
            ->getMock();

        $SMSAPIMock->expects($this->any())
            ->method('makeClient')
            ->will($this->returnValue(
                $this->makeHttpClientMock()
            ));

        /** @var Adapter\SMSAPI $SMSAPIMock */
        $SMSAPIMock->setServiceLocator($this->getServiceManager());
        $SMSAPIMock->setEventManager($this->makeEventManagerMock());

        $sendRequest = Bootstrap::getMethod('SMS\Model\Adapter\SMSAPI', 'sendRequest');

        $actual = $sendRequest->invokeArgs(
            $SMSAPIMock,
            array(
                $this->makeItem()
            )
        );

        $this->assertEquals('', $actual);
    }

    /**
     *
     */
    public function testSendWithEmptyResponseShouldThrowException()
    {
        $this->setExpectedException('SMS\Exception\AdapterInternalError');

        $obj = $this->makeSMSAPIMock();
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $obj->send($this->makeItem());
    }

    /**
     *
     */
    public function testSendWithValidButUnknownErrorResponseShouldThrowException()
    {
        $this->setExpectedException('SMS\Exception\AdapterInternalError');

        $obj = $this->makeSMSAPIMock('ERROR:100:1');
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $obj->send($this->makeItem());
    }

    /**
     *
     */
    public function testSendWithValidButAuthErrorResponseShouldThrowException()
    {
        $this->setExpectedException('SMS\Exception\AdapterInternalError');

        $obj = $this->makeSMSAPIMock('ERROR:101:1');
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $obj->send($this->makeItem());
    }

    /**
     *
     */
    public function testSendWithValidResponseShouldReturnResultObject()
    {
        $creditUsed = 2;
        $smsId = 7;

        $obj = $this->makeSMSAPIMock('SUCCESS:' . $smsId . ':' . $creditUsed);
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $expectedClass = 'SMS\Model\Struct\Result';
        /** @var Struct\Result $actual */
        $actual = $obj->send($this->makeItem());

        $this->assertInstanceOf($expectedClass, $actual);
        $this->assertEquals($creditUsed, $actual->getCreditUsed());
        $this->assertEquals(array($smsId), $actual->getSmsIds());
    }
}