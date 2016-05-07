<?php
/**
 * Cache Service Layer
 *
 * @category  Service
 * @package   Service_Cache
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace System\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Mvc\MvcEvent;
use Zend\Cache\Storage\TaggableInterface;
use Zend\Cache\Storage\StorageInterface;

class ServiceCache implements ServiceLocatorAwareInterface
{
	
	protected $serviceManager;
	protected $serviceLocator;
	
	const CACHE_PAGE	 = 0x1;
	const CACHE_DATABASE = 0X2;
	
	protected $tagsPrefix = array('page' => 'cache_page_', 
								  'database' => 'cache_db_');
	
	private   $cacheStorage;
	protected $options;
	
	public function __construct($serviceManager) {
		
		$this->serviceManager = $serviceManager;
		return $this;
		 
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
    /**
	* Check if a page is saved in the cache and return contents. Return null when no item is found.
	*/
    public function load()
    {
        $id = $this->createId();
        if ($this->getCacheStorage()->hasItem($id)) {
            return $this->getCacheStorage()->getItem($id);
        }

        return null;
    }

    /**
	* Save the page contents to the cache storage.
	*/
    public function save(MvcEvent $e)
    {
        $shouldCache = false;
        $tags = array();
        /** @var $strategy \StrokerCache\Strategy\StrategyInterface */
        foreach ($this->getStrategies() as $strategy) {
            if ($strategy->shouldCache($e)) {
                $shouldCache = true;
                if ($this->getCacheStorage() instanceof TaggableInterface) {
                    $tags = array_merge($tags, $this->getTags($e));
                }
            }
        }

        if ($shouldCache) {
            $id = $this->createId();
            $content = $e->getResponse()->getContent();
            $this->getCacheStorage()->setItem($id, $content);
            if ($this->getCacheStorage() instanceof TaggableInterface) {
                $this->getCacheStorage()->setTags($id, $tags);
            }
        }
    }

    /**
	* @param array $tags
	* @return bool
	*/
    public function clearByTags(array $tags = array())
    {
        if (!$this->getCacheStorage() instanceof TaggableInterface) {
            return false;
        }
        $tags = array_map(
            function ($tag) { return self::TAG_PREFIX . $tag; },
            $tags
        );
        return $this->getCacheStorage()->clearByTags($tags);
    }

    /**
	* Determine the page to save from the request
	*
	* @throws \RuntimeException
	* @return string
	*/
    protected function createId($data)
    {
        /*if (!isset($_SERVER['REQUEST_URI'])) {
            throw new \RuntimeException("Can't auto-detect current page identity");
        }

        $requestUri = $_SERVER['REQUEST_URI'];

        return md5($requestUri);*/
    	return md5($data);
    }

    /**
	* Cache tags to use for this page
	*
	* @param \Zend\Mvc\MvcEvent $event
	* @return array
	*/
    public function getTags(MvcEvent $event)
    {
        $routeName = $event->getRouteMatch()->getMatchedRouteName();
        $tags = array(
            self::TAG_PREFIX . 'route_' . $routeName
        );
        foreach ($event->getRouteMatch()->getParams() as $key => $value) {
            if ($key == 'controller') {
                $tags[] = self::TAG_PREFIX . 'controller_' . $value;
            } else {
                $tags[] = self::TAG_PREFIX . 'param_' . $key . '_' . $value;
            }
        }

        return $tags;
    }

    /**
	* @return \Zend\Cache\Storage\StorageInterface
	*/
    public function getCacheStorage()
    {
        return $this->cacheStorage;
    }

    /**
	* @param \Zend\Cache\Storage\StorageInterface $cacheStorage
	*/
    public function setCacheStorage($cacheStorage)
    {
        $this->cacheStorage = $cacheStorage;
    }

    /**
	* @return \StrokerCache\Options\ModuleOptions
	*/
    public function getOptions()
    {
        return $this->options;
    }

    /**
	* @param \StrokerCache\Options\ModuleOptions $options
	*/
    public function setOptions($options)
    {
        $this->options = $options;
    }
    
    public function initialize()
    {
    	$this->cacheStorage = \Zend\Cache\StorageFactory::factory($this->options);
    }
    
    public function setItem($id, $data, $type = ServiceCache::CACHE_PAGE)
    {
    	
    	if(!$this->cacheStorage)
    		return false;
    	
    	switch($type) {
    		case ServiceCache::CACHE_PAGE:
    			$id = $this->tagsPrefix[ServiceCache::CACHE_PAGE] . '_' . $this->createId($id);
    			break;
   			case ServiceCache::CACHE_DATBASE:
   				$id = $this->tagsPrefix[ServiceCache::CACHE_DATABASE] . '_' . $this->createId($id);
   				break;
    				 
    	} 
    	
    	return $this->getCacheStorage()->setItem($this->createId($id), $data);
    	
    }

    public function getItem($id, $type = ServiceCache::CACHE_PAGE)
    {

    	if(!$this->cacheStorage)
    		return false;
    	
        switch($type) {
    		case ServiceCache::CACHE_PAGE:
    			$id = $this->tagsPrefix[ServiceCache::CACHE_PAGE] . '_' . $this->createId($id);
    			break;
   			case ServiceCache::CACHE_DATBASE:
   				$id = $this->tagsPrefix[ServiceCache::CACHE_DATABASE] . '_' . $this->createId($id);
   				break;
    	} 
    	 
    	return $this->getCacheStorage()->getItem($this->createId($id));
    	
    }

}