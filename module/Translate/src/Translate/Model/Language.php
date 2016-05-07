<?php
namespace Translate\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class Language
{
    public $id;
    public $name;
    public $locale;
    public $status;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->locale = (isset($data['locale'])) ? $data['locale'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
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
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Missing field 'Name'"
    										)
    								)
    						)
    				),
    		)));
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'locale',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    						 'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 5,
    										'max'      => 5,
    								),
    						),
    						array(
    								'name'	=> 'NotEmpty',
    								'options' => array(
    										'messages' => array(
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Missing field 'Locale'"
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
