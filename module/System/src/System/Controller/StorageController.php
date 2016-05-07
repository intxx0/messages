<?php
/**
 * File for File Storage Controller Class
 *
 * @category  User
 * @package   Storage_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2014  Extend Tecnologia
 */

namespace System\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Setting;          // <-- Add this import
use User\Form\SettingForm;       // <-- Add this import
use Zend\Session\Container;

class StorageController extends AbstractActionController
{
	
	public function __construct() {
	}
	
    public function indexAction()
    {

    	
    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	$service = $this->getService('ServiceSystem');
    	
    	$form = $service->getForm();
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		
    		$form->setData($request->getPost());
    	
    		if ($form->isValid()) {
    			$service->saveSettings($form->getData());
    			return $this->redirect()->toRoute('storages');
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
    			'selected' => 'users', 
    	));
    }
    
    public function uploadAction()
    {
    	
    	$id	  	= (int) $this->getRequest()->getPost('id');
    	$name 	= $this->getRequest()->getPost('name');
    	$file 	= $this->params()->fromFiles('file');
    	
    	$service = $this->getService('ServiceSystem');
    	
    	if(!$name||!$file)
    		return $this->redirect()->toRoute('storages');
    	 
    	$adapter = new \Zend\File\Transfer\Adapter\Http();
    	 
    	$adapter->setDestination('./temp/upload');
    	 
    	$message = null;
    	 
    	if($adapter->receive($file['name'])) {
    		
    		$ext = pathinfo('./temp/upload/' . $file['name'], PATHINFO_EXTENSION);
    		
    		if($service->isAllowedType($ext, $name)) {
    			
    			$storage = $service->getStorage($name);
    			$options = \Zend\Json\Json::decode($storage['options']);
    			 
    			if(isset($options->image)) {
    		
		    		require_once './vendor/WideImage/library/WideImage.php';
		    	
		    		$image = \WideImage::load('./temp/upload/' . $file['name']);
		    		
		    		if(isset($options->image->sizes)) {
		    			$files = array();
		    			foreach($options->image->sizes as $size) {
		    				$resizedImage = $image->resize($size->x, $size->y);
		    				$fileName = md5(time()) . '.png';
		    				$files[] = $fileName;
		    			}
		    		}
		    		
		    		if($id>0) {
		    			$savePath = "./public/images/users/{$id}/";
		    		} else {
		    			$savePath = "./public/images/users/";
		    		}
		    	
		    		if(!file_exists($savePath))
		    			@mkdir($savePath, 0777);
		    	
		    		$fileName = md5(time()) . '.png';
		    	
		    		$resizedImage->saveToFile($savePath . $fileName, 70);
		    	
		    		
	    		
    			    				
    			}
    			
    			if($id>0) {
    				if(isset($options->service)) {
    					
	    				$moduleService = $this->getService($options->service);
	    			
	    				if(method_exists($moduleService, 'uploadCallback'))
	    					$moduleService->uploadCallback($id, $fileName);
	    				
    				}
    			}
	    	
	    		$status = 1;
    		    			
    		}
    		
    	} else {
    		$status = 0;
    	}
    	
    	$this->flashMessenger()->setNamespace('user-form')->addMessage('Usuário excluído com sucesso.');
    	 
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	$view->setVariables(array('status' => $status,
    			'file' => '/images/users/' . $id . '/' . $fileName,
    			'massage' => $message));
    	 
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
