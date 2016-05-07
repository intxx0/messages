<?php
namespace User\Form;

use Zend\Form\Form;

class ForgotPasswordForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('forgot');
        $this->setAttribute('method', 'post');

        $this->add(array(
        		'name' => 'login',
        		'attributes' => array(
        				'type'  => 'text',
        				'class' => 'mdl-textfield__input',
        				'id'	=> 'login', 
        		),
        		'options' => array(
        				'label' => 'Login',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'login',
        				),
        		),
        ));
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'email',
           		'class' => 'mdl-textfield__input',
           		'id'	=> 'email',
            ),
            'options' => array(
       			'label' => 'E-Mail', 
        		'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'login',
        				),
            ),
        ));
        
		$this->add(array(
				'name' => 'submit',
				'attributes' => array(
						'type'	=> 'submit',
						'id'	=> 'button-login',
						'class' => 'mdl-button mdl-js-button mdl-button--raised', 
				),
				'options' => array(
						'label' => 'Submit',
				),
		));
               
    }
}