<?php
/**
 * Translate Service Layer
 *
 * @category  Translate
 * @package   Service_Translate
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Translate\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mail\Message;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Smtp;
use Translate\Model\Translate;

class ServiceTranslate implements ServiceLocatorAwareInterface
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
	
	public function saveTranslation($translation, $idLanguage)
	{
		$language = $this->serviceManager->get('Translate\Model\LanguageTable')->getLanguage($idLanguage);
		return $this->serviceManager->get('Translate\Model\TranslateFile')->saveTranslation($language->locale, $translation);
	}
	
	public function getTranslation($id, $idLanguage)
	{
		
		$id 		= (int) $id;
		$idLanguage = (int) $idLanguage;
		
		/*if($id<1)
			return false;*/
		
		$language = $this->serviceManager->get('Translate\Model\LanguageTable')->getLanguage($idLanguage);
		$translation = $this->serviceManager->get('Translate\Model\TranslateFile')->getTranslation($language->locale, $id);
		
		return $translation;
		
	}
	
	public function deleteTranslation($id, $idLanguage)
	{
		
		$id 		= (int) $id;
		$idLanguage = (int) $idLanguage;
	
		$language = $this->serviceManager->get('Translate\Model\LanguageTable')->getLanguage($idLanguage);
		return $this->serviceManager->get('Translate\Model\TranslateFile')->deleteTranslation($language->locale, $id);
	
	}
	
	public function deleteTranslates($ids)
	{
		if (strpos($ids, ',')>0) {
			$idArray = explode(',', $ids);
		
			foreach ($idArray as $id) {
				if (!$this->deleteTranslate($id))
					return false;
			}
			return true;
		} else {
			return $this->deleteTranslate($ids);
		}
	
	}
	
	public function getTranslations($page, $idLanguage, $search = null)
	{
		$language = $this->serviceManager->get('Translate\Model\LanguageTable')->getLanguage($idLanguage);
		$paginator = $this->serviceManager->get('Translate\Model\TranslateFile')->getTranslations($language->locale, true, $search);
		$paginator->setCurrentPageNumber((int)$page);
		$paginator->setItemCountPerPage(10);
		//print_r($paginator);
		return $paginator;
	}
	
	public function getLastId()
	{
		return $this->serviceManager->get('Translate\Model\TranslateTable')->getLastId();
	}
	
	public function saveLanguage($language)
	{
		file_put_contents("./data/translate/{$language->locale}.php", "array(\n);\n");
		return $this->serviceManager->get('Translate\Model\LanguageTable')->saveLanguage($language);
	}
	
	public function getLanguage($id)
	{
		$id = (int) $id;
	
		if ($id<1)
			return false;
	
		$language = $this->serviceManager->get('Translate\Model\LanguageTable')->getLanguage($id);
	
		return $language;
	
	}
	
	public function deleteLanguage($id)
	{
		$id = (int) $id;
	
		if ($id<1)
			return false;
	
		return $this->serviceManager->get('Translate\Model\LanguageTable')->deleteLanguage($id);
	
	}
	
	
	public function getLanguages($page)
	{
		$paginator = $this->serviceManager->get('Translate\Model\LanguageTable')->fetchAll(true);
		$paginator->setCurrentPageNumber((int)$page);
		$paginator->setItemCountPerPage(10);
		return $paginator;
	}
	
	public function getTranslateForm()
	{
		return $this->serviceManager->get('Translate\Form\TranslateForm');
	}
	
	public function getLanguageForm()
	{
		return $this->serviceManager->get('Translate\Form\LanguageForm');
	}
	
	public function getSearchForm()
	{
		return $this->serviceManager->get('Translate\Form\SearchForm');
	}
	
}
