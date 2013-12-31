<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMSTest\Model;

use SMS\Model\Adapter,
    SMS\Model\SMS,
    SMSTest\Bootstrap,
    Zend\ServiceManager\ServiceManager,
    PHPUnit_Framework_TestCase,
    SMS\Model\Struct,
    Zend\EventManager\EventManager,
    SMS\InputFilter;

/**
 * Class SMSTest
 * @package SMSTest\Model
 */
class SMSTest extends PHPUnit_Framework_TestCase implements Struct\CountryPrefixConstantsInterface
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

        /** @var Adapter\SMSAPI $SMSAPIMock */
        $SMSAPIMock->setInputFilter(new InputFilter\SMS());

        return $SMSAPIMock;
    }

    /**
     * @return Struct\SMSCollection
     */
    protected function makeSMSCollection()
    {
        $item1 = new Struct\SMS();
        $item1->setTo(new Struct\NumberTo(self::GERMANY, '456456456'));
        $item1->setFrom(new Struct\NumberFrom(self::GERMANY, '123456789'));
        $item1->setMessage(new Struct\Message('Message content!'));

        $item2 = new Struct\SMS();
        $item2->setTo(new Struct\NumberTo(self::GERMANY, '456456456'));
        $item2->setFrom(new Struct\NumberFrom(self::GERMANY, '123456789'));
        $item2->setMessage(new Struct\Message('Message content!'));

        $coll = new Struct\SMSCollection();
        $coll->attach($item1);
        $coll->attach($item2);

        return $coll;
    }

    /**
     *
     */
    public function testSendWithEmptyCollectionShouldThrowException()
    {
        $this->setExpectedException('\SMS\Exception\EmptyCollection');

        $obj = new SMS($this->makeSMSAPIMock());

        $obj->send(new Struct\SMSCollection());
    }

    /**
     *
     */
    public function testSendWithValidItems()
    {
        $creditUsedPerOneSMS = 7;

        $obj = new SMS($this->makeSMSAPIMock("SUCCESS:2:$creditUsedPerOneSMS"));
        $obj->getAdapter()->setEventManager($this->makeEventManagerMock());

        $coll = $this->makeSMSCollection();
        $actual = $obj->send($coll);

        $this->assertEquals($creditUsedPerOneSMS * $coll->count(), $actual->getCreditUsed());
    }

    /**
     *
     */
    public function testSendWithAdapterError()
    {
        $this->setExpectedException('\SMS\Exception\AdapterInternalError');

        $creditUsedPerOneSMS = 7;

        $obj = new SMS($this->makeSMSAPIMock("ERROR:2:$creditUsedPerOneSMS"));
        $obj->getAdapter()->setInputFilter(new InputFilter\SMSAPI());
        $obj->getAdapter()->setEventManager($this->makeEventManagerMock());

        $coll = $this->makeSMSCollection();
        $actual = $obj->send($coll);

        $this->assertEquals($creditUsedPerOneSMS * $coll->count(), $actual->getCreditUsed());
    }

    /**
     *
     */
    public function testSendWithInvalidItemsBecauseOfEmptyMessage()
    {
        $this->setExpectedException('SMS\Exception\InvalidItem');

        $creditUsedPerOneSMS = 7;

        $obj = new SMS($this->makeSMSAPIMock("SUCCESS:2:$creditUsedPerOneSMS"));
        $obj->getAdapter()->setEventManager($this->makeEventManagerMock());

        $item = new Struct\SMS();
        $item->setTo(new Struct\NumberTo(self::GERMANY, '987654321'));
        $item->setFrom(new Struct\NumberFrom(self::GERMANY, '123456789'));
        $item->setMessage(new Struct\Message(''));

        $coll = $this->makeSMSCollection();
        $coll->attach($item);
        $obj->send($coll);
    }

    /**
     *
     */
    public function testSendWithValidItemsButNeededFilter()
    {
        $creditUsedPerOneSMS = 7;

        $obj = new SMS($this->makeSMSAPIMock("SUCCESS:2:$creditUsedPerOneSMS"));
        $obj->getAdapter()->setEventManager($this->makeEventManagerMock());

        $item = new Struct\SMS();
        $item->setTo(new Struct\NumberTo(self::GERMANY, '987654test321'));
        $item->setFrom(new Struct\NumberFrom(self::GERMANY, '123456test789'));
        $item->setMessage(new Struct\Message('My message'));

        $coll = $this->makeSMSCollection();
        $coll->attach($item);
        $actual = $obj->send($coll);

        $this->assertEquals($creditUsedPerOneSMS * $coll->count(), $actual->getCreditUsed());
    }
}