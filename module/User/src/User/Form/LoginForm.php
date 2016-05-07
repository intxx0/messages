<?php
/**
 * File for Login Form Class
 *
 * @category  User
 * @package   User_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
	
	public function __construct($name = null) {

		parent::__construct('login');
	
	
        /*$this->setMethod('post')
             ->loadDefaultDecorators()
             ->addDecorator('FormErrors');*/

        /*$this->addElement(
            'text',
            'login',
            array(
                 'filters' => array(
                     array('StringTrim')
                 ),
                 'validators' => array(
                     'EmailAddress'
                 ),
                 'required' => true,
                 'label'    => 'Login'
            )
        );

        $this->addElement(
            'password',
            'password',
            array(
                 'filters' => array(
                     array('StringTrim')
                 ),
                 'validators' => array(
                     array(
                         'StringLength',
                         true,
                         array(
                             6,
                             999
                         )
                     )
                 ),
                 'required' => true,
                 'label'    => 'Senha'
            )
        );

        $this->addElement(
            'hash',
            'csrf',
            array(
                 'ignore' => true,
                 'decorators' => array('ViewHelper')
            )
        );*/

        /*$this->addElement(
            'submit'
            'login',
            array(
                 'ignore' => true,
                 'label' => 'Login'
            )
        );*/
		
		$tabIndex = 1;
		
		$this->add(array(
				'name' => 'login',
				'attributes' => array(
						'type'  => 'text',
						'id'	=> 'login', 
						'class'	=> 'mdl-textfield__input', 
						'tabindex' => $tabIndex++, 
				),
				'options' => array(
						'label' => 'Login',
						'label_attributes' => array(
								'class' => 'mdl-textfield__label',
								'for' => 'login_field',
						),
				),
		));
		$this->add(array(
				'name' => 'password',
				'attributes' => array(
						'type'  => 'password',
						'id'	=> 'password_field', 
						'class'	=> 'mdl-textfield__input', 
						'tabindex' => $tabIndex++, 
				),
				'options' => array(
						'label' => 'Password',
						'label_attributes' => array(
								'class' => 'mdl-textfield__label',
								'for' => 'password_field',
						),
				),
		));
		$this->add(array(
				'name' => 'connected',
				'attributes' => array(
						'type'  => 'checkbox',
						'id'	=> 'connected_field',
						'class'	=> 'mdl-checkbox__input',
						'value' => '1',
						'checked' => 'checked', 
						'tabindex' => $tabIndex++,
				),
				'options' => array(
						'label' => 'Stay me connected.',
						'label_attributes' => array(
								'class' => 'mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect',
								'for' => 'connected_field',
						),
						'checkedValue' => true,
						'uncheckedValue' => false,
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
						'label' => 'Login',
				),
		));

    }
}
