<?php
/**
 * File for Widget Controller Class
 *
 * @category  System
 * @package   Widget_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Model\Widget;          // <-- Add this import
use User\Form\WidgetForm;       // <-- Add this import
use Zend\Session\Container;

class WidgetController extends AbstractActionController
{
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
		
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usu�rios'] = $this->url()->fromRoute('users');*/
    	
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	
    	$service = $this->getService('ServiceSystem');
    	
    	$form = $service->getWidgetForm();
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		
    		$form->setData($request->getPost());
    	
    		if ($form->isValid()) {
    			$service->saveWidgets($form->getData());
    			return $this->redirect()->toRoute('widgets');
    		}
    	} else {
    		//$form->populateFieldsets($widget);
    	}
    	
    	$timezones = $service->getTimezones();
    	
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			'timezones' => $timezones, 
    			'form' => $form,
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'selected' => 'system', 
    	));
    }
    
    public function setOptionAction()
    {
    	 
    	//if(!$this->getRequest()->isXmlHttpRequest())
    	//	return $this->redirect()->toRoute('widgets');
    
    	$id = (int) $this->params()->fromPost('id', 0);
    	$value = (int) $this->params()->fromPost('value', 0);
    
    	//if(!$id)
    	//	return $this->redirect()->toRoute('widgets');
    	
    	$service = $this->getService('ServiceSystem');
    	 
    	$service->saveWidgetOption($id, $value);
    	 
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	 
    	$jsonModel = new JsonModel();
    	$jsonModel->setVariables(array('status' => 1));
    
    	return $jsonModel;
    
    }
    
    public function setPositionAction()
    {
    
    	//if(!$this->getRequest()->isXmlHttpRequest())
    	//	return $this->redirect()->toRoute('widgets');
    
    	$id = (int) $this->params()->fromPost('id', 0);
    	$top = (int) $this->params()->fromPost('top', 0);
    	$left = (int) $this->params()->fromPost('left', 0);
    	$zIndex = (int) $this->params()->fromPost('zindex', 0);
    
    	//if(!$id)
    	//	return $this->redirect()->toRoute('widgets');
    	 
    	$service = $this->getService('ServiceSystem');
    	$service->saveWidgetPosition($id, "{$top};{$left};{$zIndex}");
    
    	$view = new ViewModel();
    	$view->setTerminal(true);
    
    	$jsonModel = new JsonModel();
    	$jsonModel->setVariables(array('status' => 1));
    
    	return $jsonModel;
    
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
