<?php
/**
     * @author lucas.wawrzyniak
     * @copyright Copyright (c) 2013 Lucas Wawrzyniak
     * @licence New BSD License
     */

namespace SMS\Factory;

use SMS\Model\SMS,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface,
    SMS\Model\Adapter;

/**
 * Class SMSAPI
 * @package SMS\Factory
 */
class SMSAPI implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \SMS\Model\SMS
     */
    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new SMS(new Adapter\SMSAPI($serviceLocator));
    }
}
