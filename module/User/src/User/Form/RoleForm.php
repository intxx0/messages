<?php
/**
 * File for Role Form Class
 *
 * @category  Role
 * @package   User_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class RoleForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('role');
	
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
                'label' => 'Group Name',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'name',
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
								'1' => 'Ativo',
								'0' => 'Inativo',
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
						'label' => 'Enviar',
				),
		));
		
		$resultSet = $serviceManager->get('User\Model\ModuleTable')->fetchAll();
		
		foreach($resultSet->toArray() as $result) {
			 
			$fieldset = new Fieldset($result['name']);
			
			$resources = $serviceManager->get('User\Model\ResourceTable')->getResourcesByModule($result['id']);
			 
			foreach($resources as $resource) {
				$fieldset->add(array(
						'type' => 'Zend\Form\Element\Checkbox', 
						'useHiddenElement' => true,
						'name' => 'resource-' . $resource['id'], 
						'attributes' => array(
								'id' => 'resource-' . $resource['id'],
								'value' => '1',
								'class' => 'mdl-checkbox__input', 
						),
						'options' => array(
								'label' => $resource['name'],
								'checkedValue'   => false,
								'uncheckedValue' => true,
								'label_attributes' => array(
										'class' => 'mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect',
										'for' 	=> 'resource-' . $resource['id'],
								),
						),
						
				));
			}
			
			$this->add($fieldset);
			
		}
		

    }
    
    public function populateFieldsets($role)
    {
    	
    	foreach($this->getFieldsets() as $fieldset) {
    		$fieldName = $fieldset->getName();
    		foreach($fieldset->getElements() as $element) {
    			$element = $element->getName();
    			if(isset($role->fieldsets[$fieldName])) {
    				if(isset($role->fieldsets[$fieldName][$element])) {
    					$this->get($fieldName)->get($element)->setAttribute('checked', 'checked');
    				}
    			}
    		}
    	}
    	return;
    	 
    }
    
}
