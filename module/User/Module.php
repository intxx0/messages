<?php
namespace User;

use User\Model\User;
use User\Model\UserTable;
use User\Model\Role;
use User\Model\RoleTable;
//use User\Model\Module;
use User\Model\ModuleTable;
use User\Model\Resource;
use User\Model\ResourceTable;
use User\Model\Permission;
use User\Model\PermissionTable;
use User\Model\Log;
use User\Model\LogTable;
use User\Form\RoleForm;
use User\Event\Authentication;
use User\Service\ServiceUser;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

use Zend\Module\Consumer\AutoloaderProvider,
	Zend\EventManager\StaticEventManager;


//class Module implements AutoloaderProvider
class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    // Add this method:
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'User\Model\UserTable' =>  function($sm) {
    						$tableGateway = $sm->get('UserTableGateway');
    						$table = new UserTable($tableGateway);
    						return $table;
    					},
    					'UserTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						//$resultSetPrototype->setArrayObjectPrototype(new User());
    						return new TableGateway('tbl_users', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Model\RoleTable' =>  function($sm) {
	    					$tableGateway = $sm->get('RoleTableGateway');
	    					$table = new RoleTable($tableGateway, $sm);
	    					return $table;
    					},
    					'RoleTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Role());
	    					return new TableGateway('tbl_roles', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Model\PermissionTable' =>  function($sm) {
	    					$tableGateway = $sm->get('PermissionTableGateway');
	    					$table = new PermissionTable($tableGateway);
	    					return $table;
    					},
    					'PermissionTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Permission());
	    					return new TableGateway('rel_permissions', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Model\ModuleTable' =>  function($sm) {
	    					$tableGateway = $sm->get('ModuleTableGateway');
	    					$table = new ModuleTable($tableGateway);
	    					return $table;
    					},
    					'ModuleTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					//$resultSetPrototype->setArrayObjectPrototype(new Role());
	    					return new TableGateway('tbl_modules', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Model\ResourceTable' =>  function($sm) {
	    					$tableGateway = $sm->get('ResourceTableGateway');
	    					$table = new ResourceTable($tableGateway);
	    					return $table;
    					},
    					'ResourceTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					//$resultSetPrototype->setArrayObjectPrototype(new Role());
	    					return new TableGateway('seg_modules_resources', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Model\LogTable' =>  function($sm) {
	    					$tableGateway = $sm->get('LogTableGateway');
	    					$table = new LogTable($tableGateway);
    						return $table;
    					},
    					'LogTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					//$resultSetPrototype->setArrayObjectPrototype(new Role());
	    					return new TableGateway('seg_user_access', $dbAdapter, null, $resultSetPrototype);
    					},
    					'User\Form\UserForm' => function ($sm) {
    						return new UserForm($sm);
    					},
    					'User\Form\RoleForm' => function ($sm) {
    						return new RoleForm($sm);
    					},
    					'Event\Authentication' => function ($sm) {
    						return new Authentication($sm);
    					},
    					'ServiceUser' => function ($sm) {
    						return new ServiceUser($sm);
    					},
    			),
    	);
    }
    
    public function onBootstrap(MvcEvent $e) {
    	$eventManager = $e->getApplication()->getEventManager();
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'),1);
    }
    
    public function preDispatch($e) {
    	
    	$sm = $e->getApplication()->getServiceManager();
    	$auth = $sm->get('Event\Authentication');
    	
    	return $auth->preDispatch($e);
    }
        
}

