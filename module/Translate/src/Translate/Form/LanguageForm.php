<?php
/**
 * File for Language Form Class
 *
 * @category  Translate
 * @package   Language_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class LanguageForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('language');
	
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
            	'class'	=> 'mdl-textfield__input', 
            ),
            'options' => array(
                'label' => 'Name',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'name',
            							  ), 
            ),
        		
        ));
        $this->add(array(
        		'name' => 'locale',
        		'attributes' => array(
        				'type'  => 'text',
        				'class'	=> 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Locale',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'locale',
        				),
        		),
        
        ));
		$this->add(array(
				'type' => 'Zend\Form\Element\Select',
				'name' => 'status',
				'attributes' => array(
						'class' => 'mdl-textfield__input',
				),
				'options' => array(
						'label' => 'Status',
						'value_options' => array(
								'' => '', 
								'1' => 'Active',
								'0' => 'Inactive',
						),
						'label_attributes' => array(
								'class' => 'mdl-textfield__label',
								'for' 	=> 'status',
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
						'label' => 'Submit',
				),
		));
		
    }
    
    public function populateFieldsets($language)
    {
    	
    	foreach($this->getFieldsets() as $fieldset) {
    		$fieldName = $fieldset->getName();
    		foreach($fieldset->getElements() as $element) {
    			$element = $element->getName();
    			if(isset($language->fieldsets[$fieldName])) {
    				if(isset($language->fieldsets[$fieldName][$element])) {
    					$this->get($fieldName)->get($element)->setAttribute('checked', 'checked');
    				}
    			}
    		}
    	}
    	return;
    	 
    }
    
}
