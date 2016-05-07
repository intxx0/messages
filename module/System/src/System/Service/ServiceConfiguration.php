<?php
/**
 * System Services Configuration
 *
 * @category  Service
 * @package   Service_System
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace User\Service;

use System\Model\AccessLog;
use System\Model\AccessLogTable;
use System\Model\SystemLog;
use System\Model\SystemLogTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class ServiceConfiguration extends Config
{
	
    public function configureServiceManager(ServiceManager $serviceManager)
    {
    	
    	$serviceManager->setFactory('System\Model\AccessLogTable', function($sm) {
	    	$tableGateway = $sm->get('AccessLogTableGateway');
	    	$table = new AccessLogTable($tableGateway);
	    	return $table;
    	});
    	$serviceManager->setFactory('AccessLogTableGateway', function ($sm) {
	    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    	$resultSetPrototype = new ResultSet();
	    	//$resultSetPrototype->setArrayObjectPrototype(new User());
    		return new TableGateway('seg_user_access', $dbAdapter, null, $resultSetPrototype);
    	});
    	$serviceManager->setFactory('System\Model\SystemLogTable',  function($sm) {
	    	$tableGateway = $sm->get('SystemLogTableGateway');
	    	$table = new SystemLogTable($tableGateway);
	    	return $table;
    	});
    	$serviceManager->setFactory('SystemLogTableGateway', function ($sm) {
	    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    	$resultSetPrototype = new ResultSet();
	    	//$resultSetPrototype->setArrayObjectPrototype(new User());
	    	return new TableGateway('seg_users_logs', $dbAdapter, null, $resultSetPrototype);
    	});
    	$serviceManager->setFactory('System\Model\ModuleSettingTable',  function($sm) {
    		$tableGateway = $sm->get('ModuleSettingTableGateway');
    		$table = new ModuleSettingTable($tableGateway);
    		return $table;
    	});
    	$serviceManager->setFactory('ModuleSettingTableGateway', function ($sm) {
    		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    		$resultSetPrototype = new ResultSet();
    		//$resultSetPrototype->setArrayObjectPrototype(new User());
    		return new TableGateway('seg_modules_settings', $dbAdapter, null, $resultSetPrototype);
    	});
    	$serviceManager->setFactory('System\Service\ServiceSystem', function ($sm) {
    		return new System\Service\ServiceSystem($sm);
    	});
    	
        /*$serviceManager->setInvokableClass('UserInputFilter', 'SomeModule\InputFilter\User');
        $serviceManager->setService('Auth', new Authentication\AuthenticationService());
        $serviceManager->setAlias('SomeModule\Model\User', 'User');
        $serviceManager->setAlias('AdminUser', 'User');
        $serviceManager->setAlias('SuperUser', 'AdminUser');
        $serviceManager->setShared('UserForm', false);*/
        
    }
    
}