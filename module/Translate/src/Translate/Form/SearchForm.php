<?php
/**
 * File for Search Form Class
 *
 * @category  Translate
 * @package   Translate_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class SearchForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('translate');
	
		$tabIndex = 1;
		
        $this->add(array(
            'name' => 'search',
            'attributes' => array(
                'type'  => 'text',
            	'class'	=> 'mdl-textfield__input',
            	'id' => 'search', 
            ),
            'options' => array(
                'label' => 'Search',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'search',
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
    
}
