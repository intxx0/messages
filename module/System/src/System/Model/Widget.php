<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Widget implements InputFilterAwareInterface
{
	
	public $id;
    public $id_module;
    public $name;
    public $label;
    public $description;
    public $options;
    public $service;
    public $value;
    public $file;
    public $route;
    public $action;
    public $visible;
    public $position;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->id_module = (isset($data['id_module'])) ? $data['id_module'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->label = (isset($data['label'])) ? $data['label'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->options = (isset($data['options'])) ? $data['options'] : null;
        $this->value = (isset($data['value'])) ? $data['value'] : null;
        $this->service = (isset($data['service'])) ? $data['service'] : null;
        $this->file = (isset($data['file'])) ? $data['file'] : null;
        $this->route = (isset($data['route'])) ? $data['route'] : null;
        $this->action = (isset($data['action'])) ? $data['action'] : null;
        $this->visible = (isset($data['visible'])) ? $data['visible'] : null;
        $this->position = (isset($data['position'])) ? $data['position'] : null;
        
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