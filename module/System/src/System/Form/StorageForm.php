<?php
/**
 * File for File Storage Form Class
 *
 * @category  ModuleStorage
 * @package   System_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2014  Extend Tecnologia
 */

namespace System\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class SettingForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('setting');
		
		$this->serviceManager = $serviceManager;
	
		$tabIndex = 1;
		
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));
		$this->add(array(
				'name' => 'name',
				'attributes' => array(
						'type'  => 'text',
						'id'	=> 'username_field', 
						'class'	=> 'requred text', 
						'tabindex' => $tabIndex++, 
				),
				'options' => array(
						'label' => 'Nome<span>Nome do grupo</span>',
				),
		));
		$this->add(array(
				'type' => 'Zend\Form\Element\Select',
				'name' => 'status',
				'attributes' => array(
						'class' => 'uniform full_width',
						'style' => 'opacity:0;',
				),
				'options' => array(
						'label' => 'Status',
						'value_options' => array(
								'1' => 'Ativo',
								'0' => 'Inativo',
						),
				),
		));
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'  => 'submit',
						'value' => 'submit',
						//'id' => 'submitbutton',
				),
				'options' => array(
						'label' => 'Enviar',
				),
		));
		
		$resultSet = $serviceManager->get('ServiceSystem')->getSettings();
		
		foreach($resultSet as $module => $settings) {
			
			$fieldset = new Fieldset($module);
			
			foreach($settings as $setting) {
				
				$attributes = array(
						'id' => 'setting-' . $setting['id'],
						'value' => $setting['value'],
						'rel' => $setting['section'] . '.' . $setting['type'],
				);
				
				$options = array(
						'label' => $setting['label'],
				);
				
				$class	= '';
				$type 	= null;
				
				switch($setting['type']) {
					case '1':
						$type = 'Zend\Form\Element\Text';
						break;
					case '2':
						$type = 'Zend\Form\Element\Select';
						$attributes['class'] = 'uniform full_width';
						break;
					case '3':
						$type = 'Zend\Form\Element\Checkbox';
						$attributes['value'] = '1';
						$options = array_merge(array('checkedValue' => false, 'uncheckedValue' => true), $options);
						break;
					case '4':
						$type = 'Zend\Form\Element\Radio';
						break;
					case '5':
						$type = 'Zend\Form\Element\Textarea';
						break;
					case '6':
						$type = 'Zend\Form\Element\File';
						$attributes['value'] = str_replace('./public/', '/', $attributes['value']);
						$attributes['style'] = 'display:none;';
						$attributes['data-setting'] = $setting['name'] . '-' . $setting['id'];
						break;
					case '7':
						$type = 'Zend\Form\Element\Hidden';
						break;
					case '8':
						$type = 'Zend\Form\Element\Hidden';
						$attributes['class'] = 'date_time_picker';
						
						if($setting['value']) {
							$time = @mktime(0, 0, 0, date('m'), date('d'), date('Y')) + $setting['value'];
							$attributes['value'] = @strftime("%T", $time);
						}
						
						break;
					case '9':
						$type = 'Zend\Form\Element\Select';
						$attributes['class'] = 'uniform full_width';
						break;
				}
				
				$element = array(
						'name' => $setting['name'],
						'type' => $type,
						'attributes' => $attributes,

				);
				
				if($setting['options']) {
					
					$valueOptions = \Zend\Json\Json::decode($setting['options']);
					
					$arrayOptions = array();
					
					if($valueOptions) {
						foreach($valueOptions as $option)
							$arrayOptions[$option->value] = $option->name;
						$options['value_options'] = $arrayOptions;
					}
					
				} else if($setting['service']) {
					$options['value_options'] = $this->serviceManager->get($setting['service'])->getSettingOptions($setting['name']);
				}
				
				$element['options'] = $options;
				
				if($setting['type']=='3'&&$setting['value']=='1')
					$element['attributes']['checked'] = 'checked';
				
				$fieldset->add($element);
				
			}
			$this->add($fieldset);
		}

    }
    
    public function populateFieldsets($setting)
    {
   	
    	foreach($this->getFieldsets() as $fieldset) {
    		$fieldName = $fieldset->getName();
    		foreach($fieldset->getElements() as $element) {
    			$element = $element->getName();
    			if(isset($setting->fieldsets[$fieldName])) {
    				if(isset($setting->fieldsets[$fieldName][$element])) {
    					$this->get($fieldName)->get($element)->setAttribute('checked', 'checked');
    				}
    			}
    		}
    	}
    	return;
    	 
    }
    
    public function isValid()
    {
    	
    	foreach($this->data as $module => $settings) {
    		foreach($settings as $key => $value) {
	    		$setting = $this->serviceManager->get('ServiceSystem')->getSettingByName($key);
	    		$class = $setting->filter;
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

