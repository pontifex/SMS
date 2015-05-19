<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\InputFilter;

use Zend\InputFilter\Factory,
    Zend\InputFilter\InputFilter,
    Zend\InputFilter\InputFilterAwareInterface,
    Zend\InputFilter\InputFilterInterface;

/**
 * Class SMSAPI
 * @package SMS\InputFilter
 */
class SMSAPI implements InputFilterAwareInterface
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var InputFilterInterface
     */
    protected $inputFilter;

    /**
     * @param Factory $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return Factory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     *
     */
    public function __construct()
    {
        $this->setFactory(new Factory());
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @return InputFilterAwareInterface
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        $this->inputFilter = $inputFilter;
        return $this;
    }

    /**
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $this->prepareInputFilter();
        }

        return $this->inputFilter;
    }

    /**
     *
     */
    protected function prepareInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $this->addToFilter();
        $this->addMessageFilter();
    }

    /**
     *
     */
    protected function addToFilter()
    {
        $this->addDigitsFilter('to');
    }

    /**
     * @param $name
     */
    protected function addDigitsFilter($name)
    {
        $this->inputFilter->add($this->getFactory()->createInput(array(
            'name'     => $name,
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Digits'),
            ),
            'validators' => array(
                array(
                    'name'    => 'Digits',
                ),
            ),
        )));
    }

    /**
     *
     */
    protected function addMessageFilter()
    {
        $this->inputFilter->add($this->getFactory()->createInput(array(
            'name'     => 'message',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 1,
                    ),
                ),
            ),
        )));
    }
}
