<?php
/**
 * File for Dashboard Controller Class
 *
 * @category  Dashboard
 * @package   Index_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2014  Extend Tecnologia
 */

namespace Dashboard\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
	
	protected $user;
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
	
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	
    	$service = $this->getService('ServiceSystem');
    	$widgets = $service->getEnabledWidgets();
    	
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			'widgets' => $widgets, 
    			'selected' => 'dashboard',
    	));
    }
    
    public function getService($service)
    {
    	return $this->getServiceLocator()->get($service);
    }

}
