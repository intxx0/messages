<?php
/**
 * File for Settings Event Class
 *
 * @category  Settings
 * @package   System_Event
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013, Extend Tecnologia
 * 
 */

namespace System\Event;

use Zend\Mvc\MvcEvent as MvcEvent,
	Zend\Session\Container, 
	Zend\I18n\Translator\Translator;

class Settings
{
    protected $_userAuth = null;
    protected $_aclClass = null;
    
    protected $serviceManager;
    
    public function __construct($sm)
    {
    	$this->serviceManager = $sm;
    }

    public function preDispatch(MvcEvent $event)
    {
    	
    	$service = $this->serviceManager->get('ServiceSystem');
    	
    	$settings = $service->getSettingsByNamespace(__NAMESPACE__);
    	
    	foreach($settings as $setting) {
    		switch($setting->name) {
    			case 'system_i18n_language':
    				if($setting->value) {
    					setlocale(LC_ALL, $setting->value);
    					//locale_set_default($setting->value);
    				}
    				
    				
    				
    				/* Translate */
    				
    				if($setting->value!='en-US') {
    				
	    				$translator = $event->getApplication()->getServiceManager()->get('MvcTranslator');
	    				
	    				$languages = json_decode($setting->options);
	    				
	    				foreach($languages as $key => $language) {
	    					$translator->addTranslationFile('phparray', "./data/translate/{$language->value}.php", 'default', $language->value);
	    				}
	    				
	    				$translator->setLocale($setting->value)
	    						   ->setFallbackLocale($setting->value);
	    				
    				}
    				
    				/* Translate */
    				
    				
    				
    				break;
    			case 'system_i18n_timezone':
    				if($setting->value)
    					date_default_timezone_set($setting->value);
    				break;
    			case 'system_theme_color':
    				//if($setting->value)
    					//set_locale(LC_ALL, $setting->value);
    				break;
    			case 'system_theme_logo':
    				//if($setting->value)
    					//set_locale(LC_ALL, $setting->value);
    				break;
    			case 'system_system_logging':
    				//if($setting->value)
    					//set_locale(LC_ALL, $setting->value);
    				break;
   				case 'system_system_max_execution_time':
   					//if($setting->value)
   						//set_time_limit($setting->value);
   					break;
 				case 'system_system_memory_limit':
 					if($setting->value)
 						ini_set('memory_limit', $setting->value);
 					break;
 				case 'system_system_max_upload_size':
 					if($setting->value)
 						ini_set('upload_max_filesize', $setting->value);
 					break;
 				case 'system_session_timeout':
 					if($setting->value)
 						ini_set('session.gc_maxlifetime', $setting->value);
 					break;
 				case 'system_cache_enabled':
 					if($setting->value>0) {
 						
 						$expireSetting = $service->getSettingByName('system_cache_expire');
 							
	 					$cacheService = $this->serviceManager->get('ServiceCache');
	 					$cacheService->setOptions(array(
				            'adapter' => 'Filesystem',
					        //'plugins' => array('WriteControl', 'IgnoreUserAbort'),
					        'options' => array(
				        		'cache_dir' => './temp/cache', 
					            //'removeOnFailure' => true,
					            //'exitOnAbort' => true,
					            'ttl' => (int) $expireSetting->value
				        )));
	 					
	 					$cacheService->initialize();
	 					
 					}
 					break;
    		}
    	}
    	
    }
    	
}
