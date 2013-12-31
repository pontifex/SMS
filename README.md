ZF2 wrapper for various SMS providers.

TODO:
1) Add more unit tests.
2) Implement next adapters. (If you find this repository useful and would like to contribute new adapters, you are welcome!)

To add new SMS adapter implement \SMS\Model\Adapter\AdapterInterface (1 method - send()).

Tested on ZF 2.1.4 and PHP 5.3.2

How to use example:

Module is available via composer.
```php
{
    "require": {
        "pontifex/sms": "dev-master"
    }
}
```
Before you start:
- set credentials for chosen adapter (in our case SMSAPI) as shown in sms.config.global.php
- be sure you added "SMS" module to application.config.php

How to use example:
```php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use SMS\Model\SMS;
use SMS\Model\Struct\CountryPrefixConstantsInterface;
use SMS\Model\Struct\Message;
use SMS\Model\Struct\NumberTo;
use SMS\Model\Struct\SMSCollection;

class IndexController extends AbstractActionController implements CountryPrefixConstantsInterface
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $item1 = new \SMS\Model\Struct\SMS();
        $item1->setTo(new NumberTo(self::POLAND, '123123123'));
        $item1->setMessage(new Message('Hello world #1!'));

        $item2 = new \SMS\Model\Struct\SMS();
        $item2->setTo(new NumberTo(self::POLAND, '321321321'));
        $item2->setMessage(new Message('Hello world #2!'));

        $coll = new SMSCollection();
        $coll->attach($item1);
        $coll->attach($item2);

        /** @var SMS $sms */
        $sms = $this->getServiceLocator()->get('SMS\Factory\SMSAPITest');

        try {
            $sms->send($coll);
        } catch (\Exception $e) {
            //
        }

        return new ViewModel(array('result' => $sms->getResult()));
    }
}
```
If you would like to add eg. log sms feature, the best implement it using preSend, postSend and errorOnSend events
(example from Module.php).
```php
/**
 * @param MvcEvent $e
 */
public function onBootstrap(\Zend\Mvc\MvcEvent $e)
{
    $e->getApplication()->getEventManager()->getSharedManager()->attach(
        'SMS\Model\Adapter\AdapterAbstract',
        'SMS.postSend',
        function(\Zend\EventManager\Event $event) {
            echo 'SMS to number: ' . $event->getParam('numberTo')->getNumber() . ' is sent!<br />';
        }
    );
}
```