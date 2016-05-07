<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Mime implements InputFilterAwareInterface
{
	
	public $id;
    public $name;
    public $mime;
    public $extension;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     		= (isset($data['id'])) ? $data['id'] : null;
        $this->name 		= (isset($data['name'])) ? $data['name'] : null;
        $this->mime 		= (isset($data['mime'])) ? $data['mime'] : null;
        $this->extension 	= (isset($data['extension'])) ? $data['extension'] : null;
        
    }
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    		$factory     = new InputFactory();
    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'id',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'Int'),
    				),
    		)));
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    }
}