<?php

namespace Message\Filter;

use Zend\InputFilter\InputFilter;

class MessageFormFilter extends InputFilter
{
	
	public function __construct()
	{
		$this->add(array(
				'name' => 'message', 
				'validators' => array(
						array(
								'name' => 'NotEmpty',
								'options' => array(
										'messages' => array('isEmpty' => 'This field cannot be empty.'),
								),
						),
				),				
		));
		
	}
}