<?php
/**
 * File for User Controller Class
 *
 * @category  User
 * @package   User_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use R6\Mvc\Controller\BackendController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Model\User;          // <-- Add this import
use User\Form\UserForm;       // <-- Add this import
use Zend\Session\Container;

class UserController extends AbstractActionController
//class UserController extends BackendController
{
	protected $userTable;
	protected $roleTable;
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
	
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	$page = $this->params()->fromQuery('page', 1);
    	
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			'users' => $this->getService('ServiceUser')->getUsers($page),
    			'messages' =>  $this->flashMessenger()->getMessages(), 
    			'selected' => 'users', 
    	));
    }

	public function addAction()
    {
    	
    	//$this->breadcrumb['Novo Usuário'] = $this->url()->fromRoute('users', array('action' => 'add'));
    	
        $form = new UserForm();
        
        $resultSet = $this->getService('ServiceUser')->getRoles();
        
        $roles = array();
        
        //foreach($resultSet->toArray() as $result)
        	//$roles[$result['id']] = $result['name'];
        	
        $roles[''] = '';
        
        foreach($resultSet as $result)
        	$roles[$result->id] = $result->name;
        
        $form->get('id_role')->setAttribute('options', $roles);
        $form->get('submit')->setValue('Add');

        $service = $this->getService('ServiceUser');
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
            	
                $user->exchangeArray($form->getData());
                
                $user->status = '1';
                $service->saveUser($user);
                
                return $this->redirect()->toRoute('users');
                
            }
        } else {
        	
        	//$userTable->flushRegistries();
        	$service->flushRegistries();
        	
        	$user = new User();
        	
        	$user->status = '-1';
        	$service->saveUser($user);
        	$user->id = $service->getLastId();
        	
        	$form->get('id')->setValue($user->id);
        	
        }
        
        return array(/*'bradcrumb' => $this->breadcrumb, */
        		'id' => $user->id,
        		'form' => $form, 
        		'selected' => 'users',
        		);
    }

    public function editAction()
    {
    	
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if(!$id)
            return $this->redirect()->toRoute('users', array('action' => 'add'));
        
        //$this->breadcrumb['Editar Usuário'] = $this->url()->fromRoute('users', array('action' => 'add', 'id' => $id));
        
        $service = $this->getService('ServiceUser');
        
        $result = $service->getUser($id);
        
        $user = new User();
        $user->exchangeArray((array)$result);

        $form  = new UserForm();
        
        $resultSet = $service->getRoles();
        
        $roles = array();
        
        foreach($resultSet as $result)
        	$roles[$result->id] = $result->name;
        	//$roles[$result->id] = $result['name'];
        
        $form->get('id_role')->setAttribute('options', $roles);
        
        $form->bind($user);
        
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($user->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
            	
            	$user->exchangeArray((array)$form->getData());
            	$service->saveUser($form->getData());

                return $this->redirect()->toRoute('users');
                
            }
        } else {
        	$form->get('password')->setValue('');
        }
        
        return array(
       		//'bradcrumb' => $this->breadcrumb,
            'id' => $id,
            'form' => $form,
        	'file' => $user->file, 
       		'selected' => 'users',
        );
    }

	public function deleteAction()
    {
    	 
        //$ids = (int) $this->params()->fromRoute('id', 0);
    	$ids = $this->params()->fromRoute('id', 0);
    	
        if (!$ids) {
            return $this->redirect()->toRoute('users');
        }

        $this->getService('ServiceUser')->deleteUsers($ids);
        
        $this->flashMessenger()->setNamespace('user-form')->addMessage('Usuário excluído com sucesso.');

        return $this->redirect()->toRoute('users');
        
    }
    
    public function uploadImageAction()
    {
    	
    	$id 	 = (int) $this->getRequest()->getPost('id');
    	$file    = $this->params()->fromFiles('file');
    	
    	if(!$id||!$file)
    	 	return $this->redirect()->toRoute('users');
    	
    	$fileName = $this->getService('ServiceUser')->uploadImageFile($id, $file);
    	
    	if($fileName) {
    		$status = 1;
    		$message = 'Image upload success.';
    	} else {
    		$status = 0;
    		$message = 'Failed to upload image.';
    	}
    
    	$this->flashMessenger()->setNamespace('user-form')->addMessage('Usuário excluído com sucesso.');
    	
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	$view->setVariables(array('status' => $status,
    							  'file' => '/images/users/' . $id . '/' . $fileName, 
    							  'massage' => $message));
    	
    	return $view;
    
    }
    
    public function deleteImageAction()
    {
    	
    	if(!$this->getRequest()->isXmlHttpRequest())
    		return $this->redirect()->toRoute('users');
    	    	
    	$id = (int) $this->params()->fromRoute('id', 0);
    
    	if(!$id)
    		return $this->redirect()->toRoute('users');
    	
    	$user = $this->getService('UserTable')->getUser($id);
    	
    	if(@unlink("./public/images/users/{$id}/{$user->file}")) {
    		$this->flashMessenger()->setNamespace('user-form')->addMessage('Foto excluída com sucesso.');
    		$status = 1;
    	} else {
    		$this->flashMessenger()->setNamespace('user-form')->addMessage('Sem permissão para exclusão de arquivo. Contate o suporte.');
    		$status = 0;
    	}
    	
    	$view = new ViewModel();
    	$view->setTerminal(true);
    	
    	$jsonModel = new JsonModel();
    	$jsonModel->setVariables(array('status' => $status,
    							  /*'massage' => $message*/));
    	    	
    	return $jsonModel;
    
    }
    
    public function widgetAction()
    {
    
    	//if(!$this->getRequest()->isXmlHttpRequest())
    	//	return $this->redirect()->toRoute('users');
    	 
    	$this->layout('layout/ajax');
    	
    	$serviceSystem = $this->getService('ServiceSystem');
    	$widget = $serviceSystem->getWidgetByName('log-access');
    	
    	$serviceUser = $this->getService('ServiceUser');
    	$logs = $serviceUser->getLastAccessLogs();
    
    	//$view = new ViewModel();
    	//$view->setTerminal(true);
    	
    	return array('widget' => $widget, 
    				 'logs' => $logs);
    
    }
    
    public function getUserTable()
    {
    	if (!$this->userTable) {
    		$sm = $this->getServiceLocator();
    		$this->userTable = $sm->get('User\Model\UserTable');
    	}
    	return $this->userTable;
    }
    
    public function getRoleTable()
    {
    	if (!$this->roleTable) {
    		$sm = $this->getServiceLocator();
    		$this->roleTable = $sm->get('User\Model\RoleTable');
    	}
    	return $this->roleTable;
    }
    
    /*public function getService($service)
    {
    	$instance = &$this->{lcfirst($service)};
    	if(!$instance)
    		$instance = $this->getServiceLocator()->get("User\\Model\\{$service}");
    	return $instance;
    }*/
    
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
