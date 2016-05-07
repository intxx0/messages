<?php
/**
 * File for Setting Controller Class
 *
 * @category  System
 * @package   Setting_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2015  Extend Tecnologia
 */

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Setting;          // <-- Add this import
use User\Form\SettingForm;       // <-- Add this import
use Zend\Session\Container;
use Zend\Http\Response;

class SettingController extends AbstractActionController
{
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
	
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usu�rios'] = $this->url()->fromRoute('users');*/
    	
    	$service = $this->getService('ServiceSystem');
    	
    	$form = $service->getForm();
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		
    		$form->setData($request->getPost());
    	
    		if ($form->isValid()) {
    			$service->saveSettings($form->getData());
    			return $this->redirect()->toRoute('settings');
    		}
    	} else {
    		//$form->populateFieldsets($setting);
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
    
    public function uploadImageAction()
    {
    	
    	$id 	 = (int) $this->getRequest()->getPost('id');
    	$file    = $this->params()->fromFiles('file');
    	$name 	 = $this->getRequest()->getPost('name');
    	
    	if(!$id||!$file||!$name)
    	 	return $this->redirect()->toRoute('settings');
    	
    	$fileName = $this->getService('ServiceSystem')->uploadImageFile($name, $file);
    	
    	if($fileName) {
    		$status = 1;
    	} else {
    		$status = 0;
    	}
    
    	$this->flashMessenger()->setNamespace('setting-form')->addMessage('Imagem cadastrada com sucesso.');
    	
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	$view->setVariables(array('status' => $status,
    							  'file' => $fileName, 
    							  'id' => $name, 
    							  /*'massage' => $message*/));
    	
    	return $view;
    	
    }
    
    public function themeAction()
    {
    	 
    	$service = $this->getService('ServiceSystem');
    	
    	$scheme = $service->getColorScheme();
    	$setting = $service->getSettingByName('system_theme_background');
    	$background = str_replace('./public', '', $setting->value);
    	
    	//print_r($scheme);
    	
    	$this->getResponse()->getHeaders()->addHeaders(array('Content-Type' => 'text/css'));
    	
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	$view->setVariables(array('scheme' => $scheme, 'background' => $background));
    	
    	return $view;
        	
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
