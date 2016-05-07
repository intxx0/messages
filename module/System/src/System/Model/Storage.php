<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Storage implements InputFilterAwareInterface
{
	
	public $id;
    public $id_module;
    public $name;
    public $path;
    public $mime_type;
    public $options;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->id_module = (isset($data['id_module'])) ? $data['id_module'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->path = (isset($data['path'])) ? $data['path'] : null;
        $this->mime_type = (isset($data['mime_type'])) ? $data['mime_type'] : null;
        $this->options = (isset($data['options'])) ? $data['options'] : null;
        
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