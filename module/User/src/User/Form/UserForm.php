<?php
namespace User\Form;

use Zend\Form\Form, 
	Zend\Form\FormInterface;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('user');
        
        //$this->setUseInputFilterDefaults(false);
        
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
           		'id'  => 'id',
            ),
        ));
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
            	'class'	=> 'mdl-textfield__input', 
            ),
            'options' => array(
                'label' => 'Full Name',
            	'label_attributes' => array(
            								'class' => 'mdl-textfield__label', 
            								'for' 	=> 'name',
            							  ), 
            ),
        		
        ));
        $this->add(array(
        		'name' => 'email',
        		'attributes' => array(
        				'type'  => 'text',
        				'class'	=> 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'E-Mail Address',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'email',
        				),
        		),
        
        ));
        $this->add(array(
        		'name' => 'file',
        		'attributes' => array(
        				'type'  => 'file',
        				'id'	=> 'file', 
        				'style'	=> 'display:none;', 
        				'accept' => 'image/*', 
        		),
        		'options' => array(
        				'label' => 'Foto',
        		),
        ));
        $this->add(array(
        		'type' => 'Zend\Form\Element\Select',
        		'name' => 'id_role',
        		'attributes' => array(
        				'id'	=> 'id-role', 
        				'class' => 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Group',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'id_role',
        				),
        		),
        ));
        $this->add(array(
        		'type' => 'Zend\Form\Element\Textarea',
        		'name' => 'description',
        		'attributes' => array(
        				'rows' => '2', 
        				'cols' => '20', 
        				'style' => 'width: 6px; height: auto; overflow: hidden;',
        				'class'	=> 'mdl-textfield__input', 
        		),
        		'options' => array(
        				'label' => 'Login',
        		),
        ));
        $this->add(array(
        		'name' => 'login',
        		'attributes' => array(
        				'type'  => 'text',
        				'id'	=> 'login',
        				'class' => 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Login',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'login',
        				),
        		),
        ));
        $this->add(array(
        		'name' => 'password',
        		'attributes' => array(
        				'type'  => 'password',
        				'id'	=> 'password',
        				'class' => 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Password',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'password',
        				),
        		),
        ));
        $this->add(array(
        		'name' => 'password_confirm',
        		'attributes' => array(
        				'type'  => 'password',
        				'id'	=> 'password_confirm',
        				'class' => 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Confirm Password',
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'password_confirm',
        				),
        		),
        ));
        $this->add(array(
        		'type' => 'Zend\Form\Element\Select',
        		'name' => 'status',
        		'attributes' => array(
        				'class' => 'mdl-textfield__input',
        		),
        		'options' => array(
        				'label' => 'Status',
        				'value_options' => array(
        						'1' => 'Ativo',
        						'0' => 'Inativo',
        				),
        				'label_attributes' => array(
        						'class' => 'mdl-textfield__label',
        						'for' 	=> 'id_role',
        				),
        		),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
    
    public function populateValues($data)
    {
    	foreach($data as $key=>$row)
    	{
    		if (is_array(@json_decode($row))){
    			$data[$key] =   new \ArrayObject(\Zend\Json\Json::decode($row), \ArrayObject::ARRAY_AS_PROPS);
    		}
    	}
    	 
    	parent::populateValues($data);
    }
        
}