<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model\Adapter;

use SMS\Model\Adapter,
    SMS\Model\Struct,
    SMSTest\Bootstrap,
    SMS\Exception;

/**
 * Class SMSAPITestTest
 * @package SMSTest\Model\Adapter
 */
class SMSAPITestTest extends AbstractAdapter
{
    /**
     *
     */
    public function testPrepareUrlShouldReturnSpecificString()
    {
        $config = array(
            'smsapi' => array(
                'url' => 'URL',
                'username' => 'USERNAME',
                'password' => 'PASSWORD'
            )
        );

        $this->getServiceManager()->setService('Config', $config);

        $obj = new Adapter\SMSAPITest($this->getServiceManager());
        $prepareUrl = Bootstrap::getMethod('SMS\Model\Adapter\SMSAPITest', 'prepareUrl');

        $this->assertEquals(
            'URL?username=USERNAME&password=PASSWORD&to=0049456456456&message=Message+content%21&test=1',
            $prepareUrl->invokeArgs(
                $obj,
                array(
                    $this->makeItem()
                )
            )
        );
    }

    /**
     *
     */
    public function testPreSendShouldReturnVoid()
    {
        $obj = new Adapter\SMSAPITest($this->getServiceManager());

        $obj->preSend($this->makeItem());
    }

    /**
     *
     */
    public function testPostSendShouldReturnVoid()
    {
        $obj = new Adapter\SMSAPITest($this->getServiceManager());

        $obj->postSend($this->makeItem(), new Struct\Result());
    }

    /**
     *
     */
    public function testErrorOnSendShouldReturnVoid()
    {
        $obj = new Adapter\SMSAPITest($this->getServiceManager());

        $obj->errorOnSend($this->makeItem(), new Exception\AdapterInternalError());
    }
}