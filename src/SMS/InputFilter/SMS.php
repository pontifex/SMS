<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

namespace SMS\InputFilter;

use Zend\InputFilter\InputFilter;

/**
 * Class SMS
 * @package SMS\InputFilter
 */
class SMS extends SMSAPI
{
    /**
     * @return InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $this->inputFilter = $inputFilter;

            $this->addFromFilter();
            $this->addToFilter();
            $this->addMessageFilter();
        }

        return $this->inputFilter;
    }

    /**
     *
     */
    protected function addFromFilter()
    {
        $this->inputFilter->add($this->getFactory()->createInput(array(
            'name'     => 'from',
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
}