<?php
/**
 * Message Service Layer
 *
 * @category  Message
 * @package   Service_Message
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Message\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface, 
    Zend\ServiceManager\ServiceLocatorInterface, 
    Message\Model\Message, 
    Zend\Session\Container;

class ServiceMessage implements ServiceLocatorAwareInterface
{
	
	protected $serviceManager;
	protected $serviceLocator;
	
	public function __construct($serviceManager) {
		$this->serviceManager = $serviceManager;
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function saveMessage($message)
	{
	    
	    $session = new Container('base');
	    $user = $session->offsetGet('user');
	    
	    if($user)
	       $message->id_user = $session->offsetGet('user')->id;
	    else
	       $message->id_user = 0;
	        
		return $this->serviceManager->get('Message\Model\MessageTable')->saveMessage($message);
	    
	}
	
	public function getMessage($id)
	{
		$id = (int) $id;
	
		if ($id<1)
			return false;
	
		$message = $this->serviceManager->get('Message\Model\MessageTable')->getMessage($id);
	
		return $message;
	
	}
	
	public function deleteMessage($id)
	{
		$id = (int) $id;
	
		if ($id<1)
			return false;
	
		return $this->serviceManager->get('Message\Model\MessageTable')->deleteMessage($id);
	
	}
	
	
	public function getMessages()
	{
		return $this->serviceManager->get('Message\Model\MessageTable')->getMessages();
	}
	
	public function getMessageForm()
	{
		return $this->serviceManager->get('Message\Form\MessageForm');
	}
	
}
