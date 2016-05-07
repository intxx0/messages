<?php
namespace System;

use Zend\Module\Consumer\AutoloaderProvider,
	Zend\EventManager\StaticEventManager,  
	//System\Service\ServiceConfiguration;
	System\Model\SystemLog, 
	System\Model\SystemLogTable,
	System\Model\Setting,
	System\Model\SettingTable,
	System\Model\Timezone,
	System\Model\TimezoneTable,
	System\Model\Storage,
	System\Model\StorageTable,
	System\Model\Mime,
	System\Model\MimeTable,
	System\Model\Widget,
	System\Model\WidgetTable,
	System\Model\SystemModule,
	System\Model\SystemModuleTable,
	System\Service\ServiceTimezone,
	System\Service\ServiceCache,
	System\Service\ServiceStorage,
	System\Service\ServiceSystem,
	System\Form\SettingForm, 
	System\Form\WidgetForm,
	System\Event\Settings,
	Zend\Db\ResultSet\ResultSet, 
	Zend\Db\TableGateway\TableGateway, 
	Zend\Mvc\MvcEvent;

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
    
    public function getServiceConfig()
    {
    	//return new Service\ServiceConfiguration();
    	return array(
    			'factories' => array(
    					'System\Model\SystemLogTable' =>  function($sm) {
	    					$tableGateway = $sm->get('SystemLogTableGateway');
	    					$table = new SystemLogTable($tableGateway);
	    					return $table;
    					},
    					'SystemLogTableGateway' => function ($sm) {
					    	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					    	$resultSetPrototype = new ResultSet();
					    	//$resultSetPrototype->setArrayObjectPrototype(new User());
					    	return new TableGateway('seg_modules_logs', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\SettingTable' =>  function($sm) {
	    					$tableGateway = $sm->get('SettingTableGateway');
	    					$table = new SettingTable($tableGateway);
	    					return $table;
    					},
    					'SettingTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Setting());
	    					return new TableGateway('seg_modules_settings', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\TimezoneTable' =>  function($sm) {
	    					$tableGateway = $sm->get('TimezoneTableGateway');
	    					$table = new TimezoneTable($tableGateway);
	    					return $table;
    					},
    					'TimezoneTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Timezone());
	    					return new TableGateway('tbl_timezones', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\StorageTable' =>  function($sm) {
	    					$tableGateway = $sm->get('StorageTableGateway');
	    					$table = new StorageTable($tableGateway);
	    					return $table;
    					},
    					'StorageTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					//$resultSetPrototype->setArrayObjectPrototype(new Storage());
	    					return new TableGateway('seg_modules_storages', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\MimeTable' =>  function($sm) {
	    					$tableGateway = $sm->get('MimeTableGateway');
	    					$table = new MimeTable($tableGateway);
	    					return $table;
    					},
    					'MimeTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Mime());
	    					return new TableGateway('tbl_mime_types', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\WidgetTable' =>  function($sm) {
    						$tableGateway = $sm->get('WidgetTableGateway');
    						$table = new WidgetTable($tableGateway);
    						return $table;
    					},
    					'WidgetTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Widget());
    						return new TableGateway('seg_modules_widgets', $dbAdapter, null, $resultSetPrototype);
    					},
    					'System\Model\SystemModuleTable' =>  function($sm) {
    						$tableGateway = $sm->get('SystemModuleTableGateway');
    						$table = new SystemModuleTable($tableGateway);
    						return $table;
    					},
    					'SystemModuleTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new SystemModule());
    						return new TableGateway('tbl_modules', $dbAdapter, null, $resultSetPrototype);
    					},
    						
    					'System\Form\SettingForm' => function ($sm) {
    						return new SettingForm($sm);
    					},
    					'System\Form\WidgetForm' => function ($sm) {
    						return new WidgetForm($sm);
    					},
    					'Event\Settings' => function ($sm) {
    						return new Settings($sm);
    					},
    					'ServiceTimezone' => function ($sm) {
    						return new ServiceTimezone($sm);
    					},
    					'ServiceCache' => function ($sm) {
    						return new ServiceCache($sm);
    					},
    					'ServiceStorage' => function ($sm) {
    						return new ServiceStorage($sm);
    					},
    					'ServiceSystem' => function ($sm) {
    						return new ServiceSystem($sm);
    					},
    				));
    }
    
    public function onBootstrap(MvcEvent $e) {
    	$eventManager = $e->getApplication()->getEventManager();
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'),1);
    }
    
    public function preDispatch($e) {
    	 
    	$sm = $e->getApplication()->getServiceManager();
    	$auth = $sm->get('Event\Settings');
    	 
    	return $auth->preDispatch($e);
    }
    
}
