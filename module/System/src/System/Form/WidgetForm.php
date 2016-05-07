<?php
/**
 * File for Modules Widgets Form Class
 *
 * @category  ModuleWidget
 * @package   User_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace System\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class WidgetForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('widget');
		
		$this->serviceManager = $serviceManager;
	
		$tabIndex = 1;
		
		$resultSet = $serviceManager->get('ServiceSystem')->getWidgets(false);
		
		foreach($resultSet as $widget) {
			
			$fieldset = new Fieldset($widget['name']);
			$fieldset->setLabel($widget['label']);
			$fieldset->setAttribute('data-description', $widget['description']);
			$fieldset->setAttribute('data-image', $widget['file']);
			
			$attributes = array(
					'id' => 'widget-' . $widget['id'],
					'rel' => $widget['label'],
					'tabindex' => $tabIndex++,
			);
			
			//if($widget['value']>0)
				//$attributes['checked'] = 'checked';
			
			$element = array(
					'name' => $widget['id'],
					'type' => 'Zend\Form\Element\Radio',
					'attributes' => $attributes,
					'options' =>  array(
						'label' => $widget['label'],
						'label_attributes' => array('for' => 'dynamic_on'),
						'value_options' => array(
								'0' => 'Não',
								'1' => 'Sim',
						),							
					)
			);
				
			$fieldset->add($element);
			
			if($widget['value']>0) {
				$fieldset->get($widget['id'])->setCheckedValue($widget['value]']);
				$fieldset->get($widget['id'])->setChecked(true);
				//var_dump($fieldset->get($widget['id'])->isChecked());
			}
				
			$this->add($fieldset);
			
		}

    }
    
    public function populateFieldsets($widget)
    {
   	
    	foreach($this->getFieldsets() as $fieldset) {
    		$fieldName = $fieldset->getName();
    		foreach($fieldset->getElements() as $element) {
    			$element = $element->getName();
    			if(isset($widget->fieldsets[$fieldName])) {
    				if(isset($widget->fieldsets[$fieldName][$element])) {
    					$this->get($fieldName)->get($element)->setAttribute('checked', 'checked');
    				}
    			}
    		}
    	}
    	return;
    	 
    }
    
    public function isValid()
    {
    	
    	foreach($this->data as $module => $widgets) {
    		foreach($widgets as $key => $value) {
	    		$widget = $this->serviceManager->get('ServiceSystem')->getWidgetByName($key);
	    		$class = $widget->filter;
	    		$inputFilter = new $class($key);
	    		$this->setInputFilter($inputFilter);
	    		if(!parent::isValid()) {
	    			return 'key=' . $key;
	    			return false;
	    		}
    		}
    	}
    	
    	return true;
    	
    }
    
}

