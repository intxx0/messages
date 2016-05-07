<?php
namespace Message;

use Message\Model\Message;
use Message\Model\MessageTable;
use Message\Form\MessageForm;
use Message\Service\ServiceMessage;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

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
    	return array(
    			'factories' => array(
    					'Message\Model\MessageTable' =>  function($sm) {
	    					$tableGateway = $sm->get('MessageTableGateway');
	    					$table = new MessageTable($tableGateway, $sm);
	    					return $table;
    					},
    					'MessageTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					//$resultSetPrototype->setArrayObjectPrototype(new Message());
	    					return new TableGateway('tbl_messages', $dbAdapter, null, $resultSetPrototype);
    					},
    					'ServiceMessage' => function ($sm) {
    						return new ServiceMessage($sm);
    					},
    					'Message\Form\MessageForm' => function ($sm) {
    						return new MessageForm($sm);
    					},
    			),
    	);
    }
        
}

