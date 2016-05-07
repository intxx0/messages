<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class SettingInputFilter extends InputFilter
{

	public function __construct($name)
	{
		
   		$factory     = new InputFactory();
   		
   		switch($name) {
   			case 'system_i18n_language':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_i18n_language',
		    			'required' => true,
		    			'filters'  => array(
		                        array('name' => 'StripTags'),
		                        array('name' => 'StringTrim'),
		                ),
		    	)));
		    	break;
   			case 'system_i18n_timezone':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_i18n_timezone',
		    			'required' => true,
		    			'filters'  => array(
		                        array('name' => 'StripTags'),
		                        array('name' => 'StringTrim'),
		                ),
		    	)));
   			case 'system_theme_color':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_theme_color',
		    			'required' => true,
		    			'filters'  => array(
		                        array('name' => 'StripTags'),
		                        array('name' => 'StringTrim'),
		                ),
		    	)));
		    	break;
   			case 'system_theme_logo':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_theme_logo',
		    			'required' => true,
		    			'filters'  => array(
		                        array('name' => 'StripTags'),
		                        array('name' => 'StringTrim'),
		                ),
		    	)));
		    	break;
   			case 'system_system_logging':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_system_logging',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_system_max_execution_time':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_system_max_execution_time',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_system_memory_limit':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_system_memory_limit',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_system_max_upload_size':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_system_max_upload_size',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_system_timeout':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_system_timeout',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_cache_enabled':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_cache_enabled',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   			case 'system_cache_expire':
		    	$this->add($factory->createInput(array(
		    			'name'     => 'system_cache_expire',
		    			'required' => true,
		    			'filters'  => array(
		    					array('name' => 'Int'),
		    			),
		    	)));
		    	break;
   		}
		    
    	return $this;
    	
    }
    
}