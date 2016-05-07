<?php
/**
 * File for Module Controller Class
 *
 * @category  System
 * @package   System_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2015  Extend Tecnologia
 */

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Model\System;          // <-- Add this import
use User\Form\SystemForm;       // <-- Add this import
use Zend\Session\Container;

class ModuleController extends AbstractActionController
{
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
		
	public function widgetAction()
	{
	
		//if(!$this->getRequest()->isXmlHttpRequest())
		//	return $this->redirect()->toRoute('users');
		
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	
		$this->layout('layout/ajax');
		 
		$serviceSystem = $this->getService('ServiceSystem');
		$widget = $serviceSystem->getWidgetByName('modules');
		$modules = $serviceSystem->getModules();
	
		//$view = new ViewModel();
		//$view->setTerminal(true);
		 
		return array('widget' => $widget,
					 'modules' => $modules);
	
	}
	
	public function getService($service)
	{
		return $this->getServiceLocator()->get($service);
	}
	
}
