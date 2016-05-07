<?php
return array(
    'modules' => array(
        'Application',
   		'Dashboard',
        'User',
    	'System',
   		'Translate',
        'Message', 
    	/*%module-install%*/
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
