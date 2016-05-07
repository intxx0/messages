<?php
namespace User\Model;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import

class User
{
    public $id;
    public $id_role;
    public $name;
    public $email;
    public $login;
    public $password;
    public $description;
    public $file;
    public $status;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->id_role    = (isset($data['id_role'])) ? $data['id_role'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->email  = (isset($data['email'])) ? $data['email'] : null;
        $this->login = (isset($data['login'])) ? $data['login'] : null;
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->file = (isset($data['file'])) ? $data['file'] : null;
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
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'email',
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
    										'max'      => 200,
    								),
    						),
    						array(
    								'name'	=> 'EmailAddress'
    						),*/
    						array(
    								'name'	=> 'NotEmpty',
    								'options' => array(
    										'messages' => array(
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Preencha o campo 'E-Mail'"
    										)
    								)
    						)
    				),
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'login',
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
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Preencha o campo 'Login'"
    										)
    								)
    						)
    				),
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'password',
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
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Preencha o campo 'Senha'"
    										)
    								)
    						)
    				),
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'password_confirm',
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
    												\Zend\Validator\NotEmpty::IS_EMPTY => "Preencha o campo 'Confirmar Senha'"
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
