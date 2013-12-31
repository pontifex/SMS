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
 * Class OVHTest
 * @package SMSTest\Model\Adapter
 */
class OVHTest extends AbstractAdapter
{
    /**
     *
     */
    public function testMakeClient()
    {
        $config = array(
            'ovh' => array(
                'url' => 'URL',
                'account' => '12345654321',
                'username' => 'USERNAME',
                'password' => 'PASSWORD'
            )
        );

        $this->getServiceManager()->setService('Config', $config);

        $obj = new Adapter\OVH($this->getServiceManager());
        $makeClient = Bootstrap::getMethod('SMS\Model\Adapter\OVH', 'makeClient');

        $actual = $makeClient->invokeArgs(
            $obj,
            array(
                $this->makeItem()
            )
        );

        $this->assertEquals('GET', $actual->getMethod());
        $this->assertEquals(
            'account=12345654321&login=USERNAME&password=PASSWORD&from=0049123456789&to=0049456456456&message=Message+content%21&contentType=application/json',
            $actual->getUri()->getQuery()
        );
    }

    /**
     *
     */
    public function testSendRequest()
    {
        $OVHMock = $this->getMockBuilder('\SMS\Model\Adapter\OVH')
            ->disableOriginalConstructor()
            ->setMethods(array('makeClient'))
            ->getMock();

        $OVHMock->expects($this->any())
            ->method('makeClient')
            ->will($this->returnValue(
                $this->makeHttpClientMock()
            ));

        /** @var Adapter\OVH $OVHMock */
        $OVHMock->setServiceLocator($this->getServiceManager());
        $OVHMock->setEventManager($this->makeEventManagerMock());

        $sendRequest = Bootstrap::getMethod('SMS\Model\Adapter\OVH', 'sendRequest');

        $actual = $sendRequest->invokeArgs(
            $OVHMock,
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

        $obj = $this->makeOVHMock('{"status":101}');
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

        $obj = $this->makeOVHMock('{"status":101,"creditLeft":"1007","SmsIds":[]}');
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $obj->send($this->makeItem());
    }

    /**
     *
     */
    public function testSendWithValidResponseShouldReturnResultObject()
    {
        $creditLeft = 2;
        $smsId = 7;

        $obj = $this->makeOVHMock('{"status":100,"creditLeft":"' . $creditLeft . '","SmsIds":["' . $smsId . '"]}');
        $obj->setServiceLocator($this->getServiceManager());
        $obj->setEventManager($this->makeEventManagerMock());

        $expectedClass = 'SMS\Model\Struct\Result';
        /** @var Struct\Result $actual */
        $actual = $obj->send($this->makeItem());

        $this->assertInstanceOf($expectedClass, $actual);
        $this->assertEquals($creditLeft, $actual->getCreditLeft());
        $this->assertEquals(array($smsId), $actual->getSmsIds());
    }
}