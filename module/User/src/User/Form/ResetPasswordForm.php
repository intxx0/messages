<?php
namespace User\Form;

use Zend\Form\Form;

class ResetPasswordForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('reset');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
        		'name' => 'uid',
        		'attributes' => array(
        				'type'  => 'hidden',
        				'id'  => 'uid',
        		),
        ));

        $this->add(array(
        		'name' => 'password',
        		'attributes' => array(
        				'type'  => 'password',
        				'class'	=> 'mdl-textfield__input',
        				'id'	=> 'password', 
        		),
        		'options' => array(
        				'label' => 'Password',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' => 'password',
        				),
        		),
        ));
        
        $this->add(array(
            'name' => 'password_confirm',
            'attributes' => array(
                	'type'  => 'password',
            		'class'	=> 'mdl-textfield__input',
            		'id'	=> 'password_confirm', 
            ),
            'options' => array(
                'label' => 'Password Confirm',
            		'label_attributes' => array(
            				'class' => 'mdl-textfield__label',
            				'for' => 'password_confirm',
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