<?php
namespace User\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Role
{
    public $id;
    public $name;
    public $status;
    public $fieldsets;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        
        $this->fieldsets = array();
        
        foreach($data as $key => $fieldset) {
        	if(is_array($fieldset)) {
        		$this->fieldsets[$key] = $fieldset;
        	}
        }
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
    				'name'     => 'id',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'Int'),
    				),
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'name',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						/*array(
    								'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 1,
    										'max'      => 100,
    								),
    						),*/
    						array(
    								'name'	=> 'NotEmpty',
    								'options' => array(
    										'messages' => array(
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Preencha o campo 'Nome'"
    										)
    								)
    						)
    				),
    		)));
    		
    		foreach($form->getFieldsets() as $fieldset) {
    			
    			$fieldsetElements = array();
    			
    			foreach($fieldset->getElements() as $element) {
		    		$fieldsetElements[$element->getName()] = array('required' => false);
    			}
    			
    			$fieldsetInputFilter = $factory->createInputFilter($fieldsetElements);
    			$inputFilter->add($fieldsetInputFilter, $fieldset->getName());
    			
    		}
    		
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    	
    }
    
}
