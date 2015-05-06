<?php
/**
 * @author lucas.wawrzyniak
 * @copyright Copyright (c) 2013 Lucas Wawrzyniak
 * @licence New BSD License
 */

return array(
    'modules' => array(
        'SMS',
    ),
    'module_listener_options'   => array(
        'config_glob_paths' => array(
            '../../../config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths'      => array(
            'module',
            'vendor',
        ),
    ),
);
