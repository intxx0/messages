<?php
/**
 * File for Permission Controller Class
 *
 * @category  User
 * @package   Permission_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Permission;          // <-- Add this import
use User\Form\PermissionForm;       // <-- Add this import
use Zend\Session\Container;

class PermissionController extends AbstractActionController
{
	protected $permissionTable;
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
		$session = new Container('base');
		$this->user = $session->offsetGet('user');
		
	}
	
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	 
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			'user' => $this->user,
    			'permissions' => $this->getPermissionTable()->fetchAll(),
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'selected' => 'users', 
    	));
    }

	public function addAction()
    {
    	
    	//$this->breadcrumb['Novo Usuário'] = $this->url()->fromRoute('users', array('action' => 'add'));
    	
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	 
        $form = new PermissionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $permission = new Permission();
            $form->setInputFilter($permission->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $permission->exchangeArray($form->getData());
                $this->getPermissionTable()->savePermission($permission);

                // Redirect to list of albums
                return $this->redirect()->toRoute('permissions');
            }
        }
        return array(/*'bradcrumb' => $this->breadcrumb, */
        		'user' => $this->user,
        		'form' => $form, 
        		'selected' => 'users',
        		);
    }

    public function editAction()
    {
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('permissions', array(
                'action' => 'add'
            ));
        }
        
        //$this->breadcrumb['Editar Usuário'] = $this->url()->fromRoute('users', array('action' => 'add', 'id' => $id));
        
        $permission = $this->getPermissionTable()->getPermission($id);

        $form  = new PermissionForm();
        $form->bind($permission);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($permission->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPermissionTable()->saveUser($form->getData());

                // Redirect to list of albums
                return $this->redirect()->toRoute('permissions');
            }
        }

        return array(
       		//'bradcrumb' => $this->breadcrumb,
       		'user' => $this->user,
            'id' => $id,
            'form' => $form,
       		'selected' => 'users',
        );
    }

	public function deleteAction()
    {
    	if(!$this->userAuthentication()->hasIdentity())
    		$this->redirect()->toRoute('login');
    	 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('permissions');
        }

		$this->getPermissionTable()->deletePermission($id);
			
		return $this->redirect()->toRoute('permissions');

    }
    
    public function getPermissionTable()
    {
    	if (!$this->permissionTable) {
    		$sm = $this->getServiceLocator();
    		$this->permissionTable = $sm->get('User\Model\PermissionTable');
    	}
    	return $this->permissionTable;
    }
}

