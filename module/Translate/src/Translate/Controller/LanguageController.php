<?php
/**
 * File for Language Controller Class
 *
 * @category  Translate
 * @package   Language_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Translate\Model\Language;          // <-- Add this import
use Translate\Form\LanguageForm;       // <-- Add this import
use Zend\Session\Container;

class LanguageController extends AbstractActionController
{
	protected $languageTable;
	
	public function __construct() {
		
		//$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
		
	}
	
    public function indexAction()
    {

    	/*$this->breadcrumb = array('Dashboard' => $this->url()->fromRoute('home'));
    	$this->breadcrumb['Usuários'] = $this->url()->fromRoute('users');*/
    	
    	$page 		= $this->params()->fromQuery('page', 1);
    	
    	return new ViewModel(array(
    			//'breadcrumb' => $this->breadcrumb,
    			//'languages' => $this->getService('LanguageTable')->fetchAll(),
    			'languages' => $this->getService('ServiceTranslate')->getLanguages($page),
    			'messages' =>  $this->flashMessenger()->getMessages(),
    			'selected' => 'users', 
    	));
    }

	public function addAction()
    {
    	
    	//$this->breadcrumb['Novo Usuário'] = $this->url()->fromRoute('users', array('action' => 'add'));
    	
        //$form = $this->getServiceLocator()->get('Translate\Form\LanguageForm');
    	$form = $this->getService('ServiceTranslate')->getLanguageForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        
        if ($request->isPost()) {
        	
            $language = new Language();
            
            $form->setInputFilter($language->getInputFilter($form));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $language->exchangeArray($form->getData());
                //$this->getService('LanguageTable')->saveLanguage($language);
                $this->getService('ServiceTranslate')->saveLanguage($language);
                return $this->redirect()->toRoute('languages');
            }
            
        }
        
        return array(
        		'form' => $form, 
        		'selected' => 'users',
        		);
        
    }

    public function editAction()
    {
    	 
        $id = (int) $this->params()->fromRoute('id', 0) ? $this->params()->fromRoute('id', 0) : $this->getRequest()->getPost('id');
        
        if (!$id) {
            return $this->redirect()->toRoute('languages', array(
                'action' => 'add'
            ));
        }
        
        $language = $this->getService('ServiceTranslate')->getLanguage($id);
        
        $form = $this->getService('ServiceTranslate')->getLanguageForm();
        $form->bind($language);
        
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($language->getInputFilter($form));
            $form->setData($request->getPost());
            if ($form->isValid()) {
            	$this->getService('ServiceTranslate')->saveLanguage($language);
                return $this->redirect()->toRoute('languages');
            }
        } else {
        	$form->populateFieldsets($language);
        }

        return array(
            'id' => $id,
            'form' => $form,
       		'selected' => 'users',
        );
    }

	public function deleteAction()
    {
    	 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('languages');
        }

        $this->getService('ServiceTranslate')->deleteLanguage($id);
			
		return $this->redirect()->toRoute('languages');

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
