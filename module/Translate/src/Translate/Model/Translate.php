<?php
namespace Translate\Model;

use Zend\InputFilter\Factory as InputFactory, 
	Zend\InputFilter\InputFilter, 
	Zend\InputFilter\InputFilterAwareInterface, 
	Zend\InputFilter\InputFilterInterface;

class Translate
{

	public $id;
    public $from;
    public $to;
    protected $inputFilter;

    public function exchangeArray($data)
    {
    	
    	$this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->from = (isset($data['from'])) ? $data['from'] : null;
        $this->to 	= (isset($data['to'])) ? $data['to'] : null;
        
    }
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
    
    public function getInputFilter($form)
    {
    	
    	if (!$this->inputFilter) {
    		
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'from',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'	=> 'NotEmpty',
    								'options' => array(
    										'messages' => array(
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Missing field 'From'"
    										)
    								)
    						)
    				),
    		)));
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'to',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'	=> 'NotEmpty',
    								'options' => array(
    										'messages' => array(
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Missing field 'To'"
    										)
    								)
    						)
    				),
    		)));
    		$this->inputFilter = $inputFilter;
    	}
    		
    	return $this->inputFilter;
    	
    }
    
}
