<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class SystemModule
{
    public $id;
    public $namespace;
    public $name;
    public $description;
    public $route;
    public $type;
    public $visible;
    public $file;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->namespace = (isset($data['namespace'])) ? $data['namespace'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->route = (isset($data['route'])) ? $data['route'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->visible = (isset($data['visible'])) ? $data['visible'] : null;
        $this->file = (isset($data['file'])) ? $data['file'] : null;
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
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'name',
    				'required' => true
    		)));
    		
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    }
}
