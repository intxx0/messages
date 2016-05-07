<?php
namespace Message\Model;

use Zend\InputFilter\Factory as InputFactory, 
	Zend\InputFilter\InputFilter, 
	Zend\InputFilter\InputFilterAwareInterface, 
	Zend\InputFilter\InputFilterInterface;

class Message
{

	public $id;
    public $id_user;
    public $message;
    public $date;
    protected $inputFilter;

    public function exchangeArray($data)
    {
    	
    	$this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->id_user = (isset($data['id_user'])) ? $data['id_user'] : null;
        $this->message = (isset($data['message'])) ? $data['message'] : null;
        $this->date = (isset($data['date'])) ? $data['date'] : null;
        
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
    				'name'     => 'message',
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
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Missing field 'Message'"
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
