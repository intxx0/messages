<?php
/**
 * File for Translate Form Class
 *
 * @category  Translate
 * @package   Translate_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Form;

use Zend\Form\Form, 
	Zend\Form\Fieldset;

class TranslateForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('translate');
	
		$tabIndex = 1;
		
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));
        $this->add(array(
            'name' => 'from',
            'attributes' => array(
                'type'  => 'textarea',
            	'class'	=> 'mdl-textfield__input', 
            ),
            'options' => array(
                'label' => 'From Text',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'from',
            							  ), 
            ),
        		
        ));
        $this->add(array(
        		'name' => 'to',
        		'attributes' => array(
        				'type'  => 'textarea',
        				'class'	=> 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'To Text',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'to',
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
		
    }
    
}
