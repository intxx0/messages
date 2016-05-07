<?php
/**
 * File for Role Controller Class
 *
 * @category  User
 * @package   Role_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Role;          // <-- Add this import
use User\Form\RoleForm;       // <-- Add this import
use Zend\Session\Container;

class RoleController extends AbstractActionController
{
	protected $roleTable;
	protected $permissionTable;
	protected $resourceTable;
	
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
    			//'roles' => $this->getService('RoleTable')->fetchAll(),
    			'roles' => $this->getService('ServiceUser')->getRoles($page),
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'selected' => 'users', 
    	));
    }

	public function addAction()
    {
    	
    	//$this->breadcrumb['Novo Usuário'] = $this->url()->fromRoute('users', array('action' => 'add'));
    	
        //$form = $this->getServiceLocator()->get('User\Form\RoleForm');
    	$form = $this->getService('ServiceUser')->getRoleForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
        	
            $role = new Role();
            
            $form->setInputFilter($role->getInputFilter($form));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $role->exchangeArray($form->getData());
                //$this->getService('RoleTable')->saveRole($role);
                $this->getService('ServiceUser')->saveRole($role);
                return $this->redirect()->toRoute('roles');
            }
            
        }
        
        return array(/*'bradcrumb' => $this->breadcrumb, */
        		'form' => $form, 
        		'selected' => 'users',
        		);
        
    }

    public function editAction()
    {
    	 
        $id = (int) $this->params()->fromRoute('id', 0) ? $this->params()->fromRoute('id', 0) : $this->getRequest()->getPost('id');
        
        if (!$id) {
            return $this->redirect()->toRoute('roles', array(
                'action' => 'add'
            ));
        }
        
        //$this->breadcrumb['Editar Usuário'] = $this->url()->fromRoute('users', array('action' => 'add', 'id' => $id));
        
        //$role = $this->getService('RoleTable')->getRole($id);
        $role = $this->getService('ServiceUser')->getRole($id);
        
        //$form = $this->getServiceLocator()->get('User\Form\RoleForm');
        $form = $this->getService('ServiceUser')->getRoleForm();
        $form->bind($role);
        
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($role->getInputFilter($form));
            $form->setData($request->getPost());

            if ($form->isValid()) {
            	
            	/*print '<pre>';
            	print_r($role);
            	exit();*/
            	 
            	//$role->exchangeArray((array)$form->getData());
            	
            	/*print '<pre>';
            	print_r($role);
            	exit();*/
            	
                //$this->getService('RoleTable')->saveRole($role);
            	$this->getService('ServiceUser')->saveRole($role);

                return $this->redirect()->toRoute('roles');
            }
        } else {
        	$form->populateFieldsets($role);
        }

        return array(
       		//'bradcrumb' => $this->breadcrumb,
            'id' => $id,
            'form' => $form,
       		'selected' => 'users',
        );
    }

	public function deleteAction()
    {
    	 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('roles');
        }

		//$this->getService('RoleTable')->deleteRole($id);
        $this->getService('ServiceUser')->deleteRole($id);
			
		return $this->redirect()->toRoute('roles');

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
