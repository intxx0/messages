<?php
/**
 * File for Event Class
 *
 * @category  Authentication
 * @package   User_Event
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace User\Event;

use Zend\Mvc\MvcEvent as MvcEvent,
    User\Controller\Plugin\UserAuthentication as AuthPlugin,
	Zend\Permissions\Acl\Acl, 
	Zend\Permissions\Acl\Resource\GenericResource, 
	Zend\Permissions\Acl\Role\GenericRole, 
	Zend\Session\Container;

class Authentication
{
    protected $_userAuth = null;
    protected $_aclClass = null;
    
    protected $serviceManager;
    
    public function __construct($sm)
    {
    	$this->serviceManager = $sm;
    }

    public function preDispatch(MvcEvent $event)
    {
    	
        $userAuth = $this->getUserAuthenticationPlugin();
        
        $router = $event->getRouter();
        $route = $event->getRouteMatch();
        
        if(($route->getParam('controller')=='User\Controller\Auth'&&$route->getParam('action')=='login')||
           ($route->getParam('controller')=='User\Controller\Auth'&&$route->getParam('action')=='forgot')||
           ($route->getParam('controller')=='User\Controller\Auth'&&$route->getParam('action')=='reset')||
           ($route->getParam('controller')=='System\Controller\Setting'&&$route->getParam('action')=='theme')||
           ($route->getParam('controller')=='Message\Controller\Api'))
        	return;

        //print $route->getParam('controller') . "<br />" . $route->getParam('action'); exit();
        $resourceTable = $this->serviceManager->get('User\Model\ResourceTable');
		$resourceMatch = $resourceTable->getResourceByRoute($route);
		
		if($resourceMatch) {
			
	        if(!$userAuth->hasIdentity()) {
	        	$this->serviceManager->get('ServiceSystem')->systemLog("Sessao invalida.");
	        	$response = $event->getResponse();
	        	$response->setStatusCode(302);
	        	$response->getHeaders()->addHeaderLine('Location', $router->assemble(array(), array('name' => 'login')));
	        	$event->stopPropagation();
	        }
	        
	        $acl = new Acl;
	        
	        $resources = $resourceTable->fetchAll();
	        $roles	   = $this->serviceManager->get('User\Model\RoleTable')->fetchAll();
	        
	        foreach($roles as $role)
	        	$acl->addRole(new GenericRole($role->id));
	        foreach($resources as $resource)
	        	$acl->addResource(new GenericResource($resource->id));
	        
			$session = new Container('base');
			$logo = $session->offsetGet('logo');
			$user = $session->offsetGet('user');
			$title = $session->offsetGet('title');
	        
	        $permissions = $this->serviceManager->get('User\Model\PermissionTable')->getPermissions($user['id_role']);
	        
	        foreach($permissions as $permission) {
	        	$resource = $resourceTable->getResource($permission->id_resource);
	        	$acl->allow($user['id_role'], $resource['id'], $resource['action']);
	        }
	        
	        /*$allowed = $acl->isAllowed($user['id_role'], $resourceMatch['id'], $route->getParam('action'));
	        print '[role=' . $user['id_role'] . ' resource=' . $resourceMatch['id'] . ' action=' . $route->getParam('action') . ']: ' . 
	        	  ($allowed?'true':'false');*/
	        
	        if(!$acl->isAllowed($user['id_role'], $resourceMatch['id'], $route->getParam('action'))) {
	        	$this->serviceManager->get('ServiceSystem')->systemLog("Acesso nao permitido ao recurso. [role={$user['id_role']},resource={$resourceMatch['id']}]");
	        	$response = $event->getResponse();
	        	$response->setStatusCode(302);
	        	$response->getHeaders()->addHeaderLine('Location', $router->assemble(array('code' => 401), array('name' => 'error')));
	        	$event->stopPropagation();
	        }
	        
	        $view = $event->getViewModel();
	        $view->setVariable('logo', $logo);
	        $view->setVariable('user', $user);
	        $view->setVariable('title', $title);
	        
        } else {
        	$this->serviceManager->get('ServiceSystem')->systemLog("Acesso inválido.");
        	$response = $event->getResponse();
        	$response->setStatusCode(301);
        	$response->getHeaders()->addHeaderLine('Location', $router->assemble(array(), array('name' => 'login')));
        	$event->stopPropagation();
        }
        
    }

    /**
     * Sets Authentication Plugin
     *
     * @param \User\Controller\Plugin\UserAuthentication $userAuthenticationPlugin
     * @return Authentication
     */
    public function setUserAuthenticationPlugin(AuthPlugin $userAuthenticationPlugin)
    {
        $this->_userAuth = $userAuthenticationPlugin;

        return $this;
    }

    /**
     * Gets Authentication Plugin
     *
     * @return \User\Controller\Plugin\UserAuthentication
     */
    public function getUserAuthenticationPlugin()
    {
        if ($this->_userAuth === null) {
            $this->_userAuth = new AuthPlugin();
        }

        return $this->_userAuth;
    }

    /**
     * Sets ACL Class
     *
     * @param \User\Acl\Acl $aclClass
     * @return Authentication
     */
    public function setAclClass(AclClass $aclClass)
    {
        $this->_aclClass = $aclClass;

        return $this;
    }

    /**
     * Gets ACL Class
     *
     * @return \User\Acl\Acl
     */
    public function getAclClass()
    {
        if ($this->_aclClass === null) {
            $this->_aclClass = new AclClass(array());
        }

        return $this->_aclClass;
    }
}

