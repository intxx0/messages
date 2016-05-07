<?php
/**
 * File for Message Form Class
 *
 * @category  Messages
 * @package   Message_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Message\Form;

use Zend\Form\Form;

class MessageForm extends Form
{
	protected $serviceManager;
	
	public function __construct($serviceManager = null) {
		
		parent::__construct('form-message');
	
		$tabIndex = 1;
		
		$this->add(array(
				'name' => 'id',
				'attributes' => array(
						'type'  => 'hidden',
				),
		));
        $this->add(array(
            'name' => 'message',
            'attributes' => array(
                'type'  => 'text',
                'pattern' => '.{0}|.{2,140}',
            	'class'	=> 'mdl-textfield__input', 
                'id' => 'message', 
            ),
            'options' => array(
                'label' => 'Message',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'message',
            							  ), 
            ),
        		
        ));
		
    }
    
}
