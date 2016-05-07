<?php
/**
 * File for Permission Form Class
 *
 * @category  User
 * @package   User_Form
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Form;

use Zend\Form\Form;

class PermissionForm extends Form
{
	
	public function __construct($name = null) {

		parent::__construct('permission');

    }
    
}
