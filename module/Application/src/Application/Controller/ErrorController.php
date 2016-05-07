<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ErrorController extends AbstractActionController
{
	
	public function init() {
		
		$this->userAuthentication = $this->getLocator()->get('User\Controller\Plugin\UserAuthentication');
		
	}
	
    public function indexAction()
    {
    	
    	$code = $this->params()->fromRoute('code', 0);
    	
    	$description = null;
    	$message	 = null;
    	
    	switch($code) {
    		case 401:
    			$description = 'Forbidden';
    			$message	 = 'You dont have permission to access this area.';
    			$response = $this->getResponse();
    			$response->setStatusCode($code);
    			break;
    	}
    	
    	if(!$description) {
    		$this->getResponse()->setStatusCode(404);
    		return;
    	}
    	
    	return array(
    			'code' => $code, 
    			'description' => $description, 
    			'message' => $message,
    		);
        
    }
    
}
