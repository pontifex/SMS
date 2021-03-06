<?php
/**
     * Adapter for http://www.smsapi.pl/
     *
     * @author lucas.wawrzyniak
     * @copyright Copyright (c) 2013 Lucas Wawrzyniak
     * @licence New BSD License
     */

namespace SMS\Model\Adapter;

use SMS\Exception,
    SMS\Model\Struct,
    Zend\Http\Client,
    Zend\ServiceManager\ServiceLocatorInterface,
    SMS\InputFilter;

/**
 * Class SMSAPI
 * @package SMS\Model\Adapter
 */
class SMSAPI extends AdapterWebAbstract
{
    const SMS_API_ERROR_RESPONSE = 'ERROR';
    const SMS_API_AUTH_ISSUE = 101;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($serviceLocator);

        $this->setInputFilter(new InputFilter\SMSAPI());
    }

    /**
     * @param Struct\SMS $item
     * @return mixed
     */
    protected function sendRequest(Struct\SMS $item)
    {
        $client = $this->makeClient($item);
        return $client->send($client->getRequest())->getBody();
    }

    /**
     * @param Struct\SMS $item
     * @return Client
     */
    protected function makeClient(Struct\SMS $item)
    {
        $client = new Client();
        $client->setOptions(array(
            'sslverifypeer' => false
        ));

        $client->setUri($this->prepareUrl($item));
        $client->setMethod('GET');

        return $client;
    }

    /**
     * @param Struct\SMS $item
     * @return string
     */
    protected function prepareUrl(Struct\SMS $item)
    {
        $config = $this->getServiceLocator()->get('Config');

        $format = "%s?username=%s&password=%s&to=%s&message=%s";
        return sprintf(
            $format,
            $config['smsapi']['url'],
            urlencode($config['smsapi']['username']),
            urlencode($config['smsapi']['password']),
            urlencode($item->getTo()->getNumber()),
            urlencode($item->getMessage()->getContent())
        );
    }

    /**
     * @param $response
     * @return array
     */
    protected function parseResponse($response)
    {
        return explode(':', $response);
    }

    /**
     * @param $parsedResponse
     * @param $response
     */
    protected function checkParsedResponse($parsedResponse, $response)
    {
        $this->checkParsedResponseStructure($parsedResponse, $response);
        $this->checkParsedResponseContent($parsedResponse, $response);
    }

    /**
     * @param array $parsedResponse
     * @param $response
     * @throws Exception\AdapterInternalError
     */
    protected function checkParsedResponseStructure(array $parsedResponse, $response)
    {
        if (!array_key_exists(0, $parsedResponse)
            || !array_key_exists(1, $parsedResponse)
            || !array_key_exists(2, $parsedResponse)
        ) {
            throw new Exception\AdapterInternalError($response);
        }
    }

    /**
     * @param array $parsedResponse
     * @param $response
     * @throws Exception\AdapterInternalError
     * @throws Exception\AccessUnauthorized
     */
    protected function checkParsedResponseContent(array $parsedResponse, $response)
    {
        if ($parsedResponse[0] == self::SMS_API_ERROR_RESPONSE) {
            switch ($parsedResponse[1]) {
                case self::SMS_API_AUTH_ISSUE:
                    throw new Exception\AccessUnauthorized($response);
                default:
                    throw new Exception\AdapterInternalError($response);
            }
        }
    }

    /**
     * @param $parsedResponse
     * @return Struct\Result
     * @throws Exception\AdapterInternalError
     * @throws Exception\AccessUnauthorized
     */
    protected function makeResult($parsedResponse)
    {
        return new Struct\Result(
            array($parsedResponse[1]),
            null,
            $parsedResponse[2]
        );
    }
}
