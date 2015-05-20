<?php
/**
 * Adapter for http://www.ovh.com/
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
 * Class OVH
 * @package SMS\Model\Adapter
 */
class OVH extends AdapterWebAbstract
{
    const OVH_SUCCESS_CODE = 100;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        parent::__construct($serviceLocator);

        $this->setInputFilter(new InputFilter\SMS());
    }

    /**
     * @param Struct\SMS $item
     * @return \Zend\Http\Response
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
        $config = $this->serviceLocator->get('Config');
        
        $format = "%s?account=%s&login=%s&password=%s&from=%s&to=%s&message=%s&contentType=application/json";
        return sprintf(
            $format,
            $config['ovh']['url'],
            $config['ovh']['account'],
            $config['ovh']['username'],
            $config['ovh']['password'],
            urlencode($item->getFrom()->getNumber()),
            urlencode($item->getTo()->getNumber()),
            urlencode($item->getMessage()->getContent())
       	);
    }

    /**
     * @param $response
     * eg. {"status":100,"creditLeft":"1007","SmsIds":["10867690"]}
     * @return \stdClass
     */
    protected function parseResponse($response)
    {
        return json_decode($response);
    }

    /**
     * @param \stdClass $parsedResponse
     * @param $response
     * @throws Exception\AdapterInternalError
     */
    protected function checkParsedResponse(\stdClass $parsedResponse, $response)
    {
        $this->checkParsedResponseStructure($parsedResponse, $response);
        $this->checkParsedResponseContent($parsedResponse, $response);
    }

    /**
     * @param \stdClass $parsedResponse
     * @param $response
     * @throws Exception\AdapterInternalError
     */
    protected function checkParsedResponseStructure(\stdClass $parsedResponse, $response)
    {
        if (!property_exists($parsedResponse, 'status')
            || !property_exists($parsedResponse, 'creditLeft')
            || !property_exists($parsedResponse, 'SmsIds')
        ) {
            throw new Exception\AdapterInternalError($response);
        }

        return;
    }

    /**
     * @param \stdClass $parsedResponse
     * @param $response
     * @throws Exception\AdapterInternalError
     */
    protected function checkParsedResponseContent(\stdClass $parsedResponse, $response)
    {
        if ($parsedResponse->status != self::OVH_SUCCESS_CODE) {
            throw new Exception\AdapterInternalError($response);
        }

        return;
    }

    /**
     * @param \stdClass $parsedResponse
     * @return Struct\Result
     */
    protected function makeResult(\stdClass $parsedResponse)
    {
        return new Struct\Result(
            $parsedResponse->SmsIds,
            $parsedResponse->creditLeft
        );
    }
}
