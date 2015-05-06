<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\Model\Adapter;

use Zend\EventManager\EventManager,
    Zend\ServiceManager\ServiceLocatorInterface,
    SMS\Model\Struct,
    SMS\Exception,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

/**
 * Class AdapterAbstract
 * @package SMS\Model\Adapter
 */
abstract class AdapterAbstract implements AdapterInterface
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var InputFilterAwareInterface
     */
    protected $inputFilter;

    /**
     * @param EventManager $eventManager
     */
    public function setEventManager($eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param InputFilterAwareInterface $inputFilter
     */
    public function setInputFilter($inputFilter)
    {
        $this->inputFilter = $inputFilter;
    }

    /**
     * @return InputFilterAwareInterface
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);

        /** @var EventManager $eventManager */
        $eventManager = $serviceLocator->get('eventManager');
        $eventManager->setIdentifiers(__CLASS__);
        $this->setEventManager($eventManager);
    }

    /**
     * @param Struct\SMS $item
     */
    public function preSend(Struct\SMS $item)
    {
        $this->getEventManager()->trigger('SMS.preSend', null,
            array(
                'numberFrom' => $item->getFrom(),
                'numberTo'   => $item->getTo(),
                'message'    => $item->getMessage()
            )
        );
    }

    /**
     * @param Struct\SMS $item
     * @param Struct\Result $result
     */
    public function postSend(Struct\SMS $item, Struct\Result $result)
    {
        $this->getEventManager()->trigger('SMS.postSend', null,
            array(
                'numberFrom' => $item->getFrom(),
                'numberTo'   => $item->getTo(),
                'message'    => $item->getMessage(),
                'result'     => $result
            )
        );
    }

    /**
     * @param Struct\SMS $item
     * @param Exception\AdapterInternalError $exception
     */
    public function errorOnSend(Struct\SMS $item, Exception\AdapterInternalError $exception)
    {
        $this->getEventManager()->trigger('SMS.errorOnSend', null,
            array(
                'numberFrom' => $item->getFrom(),
                'numberTo'   => $item->getTo(),
                'message'    => $item->getMessage(),
                'exception'  => $exception
            )
        );
    }

    /**
     * @param Struct\SMS $item
     * @return bool
     */
    public function isItemValid(Struct\SMS $item)
    {
        $isValid = $this->getInputFilter()->getInputFilter()
            ->setData($item->toArray())
            ->setValidationGroup(InputFilterInterface::VALIDATE_ALL)
            ->isValid();

        $item->fromArray($this->getInputFilter()->getInputFilter()->getValues());

        return $isValid;
    }
}
