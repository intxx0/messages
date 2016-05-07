<?php
/**
 * Storage Service Layer
 *
 * @category  Service
 * @package   Service_Storage
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2014  Extend Tecnologia
 */

namespace System\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Storage\Model\StorageLog;

class ServiceStorage implements ServiceLocatorAwareInterface
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
	
	public function getStorages()
	{
		return $this->serviceManager->get('System\Model\StorageTable')->getStorages();
	}
	
	public function getStorageByName($name)
	{
		return $this->serviceManager->get('System\Model\StorageTable')->getStorageByName($name);
	}
	
	public function isAllowedMimeType($ext, $name)
	{
		
		$types = array();
		
		$resultSet = $this->serviceManager->get('System\Model\StorageTable')->getStorageTypesByName($name)->toArray();
		
		foreach($resultSet as $result)
			$types[] = str_replace('.', '', $result['extension']);
		
		if(in_array($ext, $types))
			return true;
		else
			return false;
		
	}
	
	public function getMimeExtension($type)
	{
		return $this->serviceManager->get('System\Model\MimeTable')->getExtensionByType($type);
	}
	
	public function uploadFile($name, $file, $id = null)
	{
		
		$adapter = new \Zend\File\Transfer\Adapter\Http();
		$adapter->setDestination('./temp/upload');
		
		if($adapter->receive($file['name'])) {
		
			$ext = pathinfo('./temp/upload/' . $file['name'], PATHINFO_EXTENSION);
		
			if(!$this->isAllowedMimeType($ext, $name))
				return false;
				 
			$storage = $this->getStorageByName($name);
			
			$options = \Zend\Json\Json::decode($storage['options']);
			
			if($id!==null)
				$savePath = "{$storage['path']}{$id}/";
			else
				$savePath = $storage['path'];
			
			$files = array();
		
			if(isset($options->image)) {
	
				require_once './vendor/WideImage/library/WideImage.php';
			  
				$image = \WideImage::load('./temp/upload/' . $file['name']);
				
				if(!file_exists($savePath))
					@mkdir($savePath, 0777);
	
				if(isset($options->image->sizes)) {
					foreach($options->image->sizes as $size) {
						$fileName = md5(time()) . $this->getMimeExtension($options->image->mime);
						if($size->w>0&&$size->h>0) {
							if (isset($options->image->crop)) {
								$width = $image->getWidth();
								$height = $image->getHeight();
								if ($width>$height) {
									$diff = $width - $height;
									$resizedImage = $image->crop(($diff / 2), 0, ($width - $diff), $height)->resize($size->w, $size->h);
								} else {
									$diff = $height - $width;
									$resizedImage = $image->crop(0, ($diff / 2), $width, ($height - $diff))->resize($size->w, $size->h);
								}
							} else {
								$resizedImage = $image->resize($size->w, $size->h);
							}
							$resizedImage->saveToFile($savePath . $fileName, $options->image->quality);
						} else {
							$image->saveToFile($savePath . $fileName, $options->image->quality);
						}
						$files[] = $fileName;
					}
				}
				
			} else {
				copy('./temp/upload/' . $file['name'], $savePath);
			}
			
			@unlink('./temp/upload/' . $file['name']);
			
			return $files;
		
		} else {
			return false;
		}
		
	}
	
	public function deleteFile($name, $file, $id = null)
	{
		
		$storage = $this->getStorageByName($name);
		
		if($storage) {

			if($id!==null)
				$path = "{$storage['path']}/{$id}/{$file}";
			else
				$path = "{$storage['path']}/{$file}";
			
			if(@unlink("{$path}/{$file}")) {
				return true;
			} else {
				return false;
			}
		
		} else {
			return false;
		}
		
	}
    
}
