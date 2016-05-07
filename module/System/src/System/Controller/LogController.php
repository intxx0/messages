<?php
/**
 * File for Log Controller Class
 *
 * @category  System
 * @package   Log_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2015  Extend Tecnologia
 */

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Model\Log;          // <-- Add this import
use User\Form\LogForm;       // <-- Add this import
use Zend\Session\Container;

class LogController extends AbstractActionController
{
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
		
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	$page = $this->params()->fromQuery('page', 1);
    	
    	$service = $this->getService('ServiceSystem');
    	$systemLogs = $service->getSystemLogs($page);
    	
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			'logs' => $systemLogs,
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'selected' => 'system', 
    	));
    }
    
    public function deleteAction()
    {
    
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('system-logs');
    	}
    
    	$this->getService('ServiceSystem')->deleteLogs();
    
    	$this->flashMessenger()->setNamespace('user-form')->addMessage('Registros de sistema excluídos com sucesso.');
    
    	return $this->redirect()->toRoute('system-logs');
    
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
