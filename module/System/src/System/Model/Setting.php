<?php
namespace System\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Setting implements InputFilterAwareInterface
{
	
	public $id;
    public $id_module;
    public $section;
    public $type;
    public $name;
    public $label;
    public $description;
    public $value;
    public $options;
    public $service;
    public $filter;
    public $visible;
    
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->id_module = (isset($data['id_module'])) ? $data['id_module'] : null;
        $this->section = (isset($data['section'])) ? $data['section'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->label = (isset($data['label'])) ? $data['label'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->value = (isset($data['value'])) ? $data['value'] : null;
        $this->options = (isset($data['options'])) ? $data['options'] : null;
        $this->service = (isset($data['service'])) ? $data['service'] : null;
        $this->filter = (isset($data['filter'])) ? $data['filter'] : null;
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