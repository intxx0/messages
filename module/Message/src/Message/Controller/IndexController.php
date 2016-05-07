<?php
/**
 * File for Messages Controller Class
 *
 * @category  Messages
 * @package   Index_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Message\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	
    public function indexAction()
    {
        
        $service = $this->getService('ServiceMessage');

    	return new ViewModel(array(
    			'messages' =>  $service->getMessages(),
    	        'form' =>  $service->getMessageForm(),
    	));
    	
    }
    
    public function getService($service)
    {
    	return $this->getServiceLocator()->get($service);
    }
    
    public function getViewHelper($helper)
    {
    	
    	$sm = $this->getEvent()->getApplication()->getServiceManager();
    	return $sm->get('viewhelpermanager')->get($helper);
    	 
    }
    
}
