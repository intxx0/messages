<?php
/**
 * File for Translate Controller Class
 *
 * @category  Translate
 * @package   Translate_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Translate\Model\Translate;
use Translate\Form\TranslateForm;
use Zend\Session\Container;

class TranslateController extends AbstractActionController
{
	protected $translateTable;
	
	public function __construct() {
	}
	
    public function indexAction()
    {

    	$page 	  	= $this->params()->fromQuery('page', 1);
    	$idLanguage = $this->params()->fromRoute('language', null);
    	$search 	= $this->params()->fromPost('search', null);
    	
    	$form = $this->getService('ServiceTranslate')->getSearchForm();
    	$form->setData($this->getRequest()->getPost());
    	
    	return new ViewModel(array(
    			'translations' => $this->getService('ServiceTranslate')->getTranslations($page, $idLanguage, $search),
    			'language' => $this->getService('ServiceTranslate')->getLanguage($idLanguage),
    			'idLanguage' => $idLanguage, 
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'form' => $form, 
    			'selected' => 'users', 
    	));
    }

	public function addAction()
    {
    	
    	$language = $this->params()->fromRoute('language', null);
    	
    	$form = $this->getService('ServiceTranslate')->getTranslateForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
        	
            $translate = new Translate();
            
            $form->setInputFilter($translate->getInputFilter($form));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $translate->exchangeArray($form->getData());
                $this->getService('ServiceTranslate')->saveTranslation($translate, $language);
                return $this->redirect()->toRoute('translations', array('language' => $language));
            }
            
        }
        
        return array(
        		'idLanguage' => $language, 
        		'form' => $form, 
        		'selected' => 'users',
        		);
        
    }

    public function editAction()
    {
    	 
        $id 		= (int) $this->params()->fromRoute('id', 0) ? $this->params()->fromRoute('id', 0) : $this->getRequest()->getPost('id');
        $language 	= $this->params()->fromRoute('language', null);
        
        /*if (!$id) {
            return $this->redirect()->toRoute('translations', array(
                'action' => 'add'
            ));
        }*/
        
        $translate = $this->getService('ServiceTranslate')->getTranslation($id, $language);
        
        $form = $this->getService('ServiceTranslate')->getTranslateForm();
        $form->bind($translate);
        
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($translate->getInputFilter($form));
            $form->setData($request->getPost());

            if ($form->isValid()) {
            	$this->getService('ServiceTranslate')->saveTranslation($translate, $language);
                return $this->redirect()->toRoute('translations', array('language' => $language));
            }
        }

        return array(
            'id' => $id,
       		'idLanguage' => $language,
            'form' => $form,
       		'selected' => 'users',
        );
    }

	public function deleteAction()
    {
    	 
        $id 		= (int) $this->params()->fromRoute('id', null);
        $language 	= $this->params()->fromRoute('language', null);
        
        if ($id===null) {
            return $this->redirect()->toRoute('translations');
        }

        $this->getService('ServiceTranslate')->deleteTranslation($id, $language);
			
		return $this->redirect()->toRoute('translations', array('language' => $language));

    }
    
    public function getService($service)
    {
    	return $this->getServiceLocator()->get($service);
    }
    
    public function getViewHelper($helper)
    {
    	
    	$sm = $this->getEvent()->getApplication()->getServiceManager();
    	return $sm->get('viewhelpermanager')->get($helper);
    	 
    }
    
}
