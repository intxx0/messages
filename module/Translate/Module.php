<?php
namespace Translate;

use Translate\Model\Translate;
use Translate\Model\TranslateFile;
use Translate\Model\Language;
use Translate\Model\LanguageTable;
use Translate\Form\TranslateForm;
use Translate\Form\LanguageForm;
use Translate\Form\SearchForm;
//use Translate\Event\Translate;
use Translate\Service\ServiceTranslate;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\MvcEvent;

use Zend\Module\Consumer\AutoloaderProvider,
	Zend\EventManager\StaticEventManager;

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
    					'Translate\Model\LanguageTable' =>  function($sm) {
	    					$tableGateway = $sm->get('LanguageTableGateway');
	    					$table = new LanguageTable($tableGateway, $sm);
	    					return $table;
    					},
    					'LanguageTableGateway' => function ($sm) {
	    					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    					$resultSetPrototype = new ResultSet();
	    					$resultSetPrototype->setArrayObjectPrototype(new Language());
	    					return new TableGateway('tbl_languages', $dbAdapter, null, $resultSetPrototype);
    					},
    					/*'Event\Translate' => function ($sm) {
    						return new Translate($sm);
    					},*/
    					'Translate\Model\TranslateFile' => function ($sm) {
    						return new TranslateFile($sm);
    					},
    					'ServiceTranslate' => function ($sm) {
    						return new ServiceTranslate($sm);
    					},
    					'Translate\Form\LanguageForm' => function ($sm) {
    						return new LanguageForm($sm);
    					},
    					'Translate\Form\TranslateForm' => function ($sm) {
    						return new TranslateForm($sm);
    					},
    					'Translate\Form\SearchForm' => function ($sm) {
    						return new SearchForm($sm);
    					},
    			),
    	);
    }
    
    /*public function onBootstrap(MvcEvent $e) {
    	$eventManager = $e->getApplication()->getEventManager();
    	$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'),1);
    }
    
    public function preDispatch($e) {
    	
    	$sm = $e->getApplication()->getServiceManager();
    	$auth = $sm->get('Event\Translate');
    	
    	return $auth->preDispatch($e);
    }*/
        
}

