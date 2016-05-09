<?php
/**
 * File for API Controller Class
 *
 * @category  Message
 * @package   Api_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Message\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Message\Model\Message;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractRestfulJsonController
{

    public function getList()
    {
    
    	$messages = $this->getService('ServiceMessage')->getMessages();
    	 
    	return new JsonModel(array(
				'status' => 'success',
    			'count' => count($messages), 
    			'type' => 'Message', 
    	        'results' => $messages->toArray(),
    	));
    	 
    }
    
    public function get($id)
    {
        
        $status = null;
        
    	$message = $this->getService('ServiceMessage')->getMessage($id);
    	
    	return new JsonModel(array(
				'status' => 'success',
    			'count' => !$message?0:1, 
    			'type' => 'Message', 
    	        'results' => array($message),
    	));
    	
    }

    public function create($data)
    {
    	
    	$service = $this->getService('ServiceMessage');
    	$form = $service->getMessageForm();

        $message = new Message();
            
        $form->setInputFilter($message->getInputFilter($form));
        $form->setData($data);

        if ($form->isValid()) {
            $message->exchangeArray($form->getData());
            $service->saveMessage($message);
        	$status = 'success';
        	$type = 'Message';
        } else {
        	$errors = $form->getMessages();
        	foreach($errors as $element => $error) {
	        	foreach($errors[$element] as $key => $value) {
	        		$data[] = array(
	        				'code' => $key, 
	        				'message' => $value,
	        				'propertyName' => $element, 
	        		);
	        	}
        	}
        	$status = 'error';
        	$type = 'Error';
        }
        
        return new JsonModel(array(
        		'status' => $status,
        		'count' => count($data),
        		'type' => $type, 
        		'results' => $data, 
   		));
        
    }

    public function getService($service)
    {
    	return $this->getServiceLocator()->get($service);
    }
    
}
