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
     *
     */
    protected function prepareInputFilter()
    {
        parent::prepareInputFilter();

        $this->addFromFilter();
    }

    /**
     *
     */
    protected function addFromFilter()
    {
        $this->addDigitsFilter('from');
    }
}
