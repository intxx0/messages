<?php
namespace User\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Resource
{
    public $id;
    public $id_module;
    public $name;
    public $controller;
    public $action;
    public $default;
    public $restricted;
    public $visible;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->id_module = (isset($data['id_module'])) ? $data['id_module'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->controller = (isset($data['controller'])) ? $data['controller'] : null;
        $this->action = (isset($data['action'])) ? $data['action'] : null;
        $this->default = (isset($data['default'])) ? $data['default'] : null;
        $this->restricted = (isset($data['restricted'])) ? $data['restricted'] : null;
        $this->visible = (isset($data['visible'])) ? $data['visible'] : null;
        
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