<?php

namespace User\Filter;

use Zend\InputFilter\InputFilter;

class UserFormFilter extends InputFilter
{
	
	public function __construct()
	{
		$this->add(array(
				'name' => 'name', 
				//'allow_empty' => false,
				'validators' => array(
						array(
								'name' => 'NotEmpty',
								'options' => array(
										'messages' => array('isEmpty' => 'Não pode estar em branco'),
								),
						),
				),				
		));
		
	}
}