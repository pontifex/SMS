<?php
/**
     * Abstract Web Adapter
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
 * Class AdapterWebAbstract
 * @package SMS\Model\Adapter
 */
abstract class AdapterWebAbstract extends AdapterAbstract
{
    /**
     * @param Struct\SMS $item
     * @return Exception\AdapterInternalError
     */
    public function send(Struct\SMS $item)
    {
        $response = $this->sendRequest($item);
        $parsedResponse = $this->parseResponse($response);

        $this->checkParsedResponse($parsedResponse, $response);

        return $this->makeResult($parsedResponse);
    }

    /**
     * @param Struct\SMS $item
     * @return mixed
     */
    abstract protected function sendRequest(Struct\SMS $item);

    /**
     * @param $response
     * @return array
     */
    abstract protected function parseResponse($response);

    /**
     * @param $parsedResponse
     * @param $response
     */
    abstract protected function checkParsedResponse($parsedResponse, $response);

    /**
     * @param array $parsedResponse
     * @return Struct\Result
     * @throws Exception\AdapterInternalError
     * @throws Exception\AccessUnauthorized
     */
    abstract protected function makeResult(array $parsedResponse);
}
