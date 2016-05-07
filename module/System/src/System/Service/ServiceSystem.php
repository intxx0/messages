<?php
/**
 * System Service Layer
 *
 * @category  Service
 * @package   Service_System
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace System\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;
use System\Model\SystemLog;

class ServiceSystem implements ServiceLocatorAwareInterface
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
	
	public function saveSystemLog(SystemLog $log)
	{
		return $this->serviceManager->get('System\Model\SystemLogTable')->saveSystemLog($log);
	}
	
	public function getModuleByNamespace($name)
	{
	
		$moduleName = substr(__NAMESPACE__, 0, strpos($name, "\\"));
		return $this->serviceManager->get('User\Model\ModuleTable')->getModuleByNamespace($moduleName);
	
	}
	
	public function systemLog($message)
	{
	
		$module = $this->getModuleByNamespace(__NAMESPACE__);
	
		$session = new Container('base');
		$user = $session->offsetGet('user');
	
		$log = new SystemLog();
	
		$log->id_user = isset($user['id'])?$user['id']:0;
		$log->id_module = $module->id;
		$log->message = $message;
	
		$this->saveSystemLog($log);
	
		return;
	
	}
	
	public function getSystemLog($id)
	{
		$id = (int) $id;
		
		if($id<1)
			return false;
		
		$log = $this->serviceManager->get('System\Model\SystemLogTable')->getSystemLog($id);
		
		return $log;
		
	}
	
	/*public function getSystemLogs()
	{
		return $this->serviceManager->get('System\Model\SystemLogTable')->fetchAll();
	}*/
	
	public function getSystemLogs($page)
	{
		$paginator = $this->serviceManager->get('System\Model\SystemLogTable')->fetchAll(true);
		$paginator->setCurrentPageNumber((int)$page);
		$paginator->setItemCountPerPage(10);
		return $paginator;
	}
	
	public function deleteSystemLogs()
	{
	
		return $this->serviceManager->get('User\Model\SystemLogTable')->deleteSystemLogs();
	
	}
	
	public function getSettings()
	{
		
		$settingsTable = $this->serviceManager->get('System\Model\SettingTable');
		$modules = $this->serviceManager->get('User\Model\ModuleTable')->getModules();
		
		$resultSet = array();
		
		//print '<pre>';
		foreach($modules->toArray() as $module) {
			$resultSet[$module['name']] = array();
			$settings = $settingsTable->getSettingsByModule($module['id']);
			foreach($settings as $setting) {
				$resultSet[$module['name']][] = (array) $setting;
			}
		}
		
		//print_r($resultSet);
		
		return $resultSet;
		
	}
	
	public function getSettingByName($name)
	{
		return $this->serviceManager->get('System\Model\SettingTable')->getSettingByName($name);
	}
	
	public function getSettingsByNamespace($namespace)
	{
		
		if(strpos($namespace, '\\')>-1)
			list($namespace, $class) = explode('\\', $namespace);
		
		return $this->serviceManager->get('System\Model\SettingTable')->getSettingsByNamespace($namespace);
		
	}
	
	public function getSettingOptions($name)
	{
		
		$options = null;
		
		switch($name) {
			case 'system_i18n_timezone':
				$timezones = $this->serviceManager->get('ServiceTimezone')->getTimezones();
				
				$options = array();
				foreach($timezones as $timezone)
					$options[$timezone->name] = $timezone->name;
				
				break;
		}
		
		return $options;
		
	}
	
	public function saveSetting($setting)
	{
		return $this->serviceManager->get('System\Model\SettingTable')->saveSetting($setting);
	}
	
	public function saveSettings($data)
	{
		if(is_array($data)) {
			foreach($data as $settings) {
				if(count($settings)>0) {
					//print_r($settings);
					foreach($settings as $key => $value) {
						$setting = $this->getSettingByName($key);
						switch($setting->type) {
							case '6':
								if(empty($value))
									$value = $setting->value;
								break;
							case '8':
								list($hour, $min, $sec) = explode(':', $value);
								$value = @mktime($hour, $min, $sec) - @mktime(0, 0, 0);
								break;
						}
						$setting->value = $value;
						$this->saveSetting($setting);
						$this->systemLog("Configuracao salva com sucesso. [setting={$key},value={$value}]");
					}
				}
			}
		} else {
			$this->systemLog("Erro ao salvar configuracoes.");
		}
		return;
	}
	
	public function getForm()
	{
		return $this->serviceManager->get('System\Form\SettingForm');
	}
	
	public function getTimezones()
	{
		return $this->serviceManager->get('ServiceTimezone')->getTimezonesPolygons(292, 146);
	}
	
	public function uploadImageFile($name, $file)
	{
		
		$files = $this->serviceManager->get('ServiceStorage')->uploadFile($name, $file);
		
		if(count($files)>0&&!empty($files[0])) {
			$storage = $this->serviceManager->get('ServiceStorage')->getStorageByName($name);
			$this->serviceManager->get('System\Model\SettingTable')->updateImageFile($name, $storage->path . $files[0]);
			$this->systemLog("Imagem cadastrada com sucesso. [storage={$name},file={$files[0]}]");
			return str_replace('./public/', '/', $storage->path) . $files[0];
		} else {
			$this->systemLog("Erro no upload de arquivo. [storage={$name},file={$file['name']}]");
			return false;
		}
	
	}
	
	
	

	public function getWidgetForm()
	{
		return $this->serviceManager->get('System\Form\WidgetForm');
	}
	
	public function getWidgets($visible = true)
	{
	
		$widgetsTable = $this->serviceManager->get('System\Model\WidgetTable');
	
		$resultSet = array();
	
		//print '<pre>';
		$widgets = $widgetsTable->getWidgets($visible);
		foreach($widgets as $widget) {
			$resultSet[] = (array) $widget;
		}
		//print_r($resultSet);
	
		return $resultSet;
	
	}
	
	public function getEnabledWidgets()
	{
	
		$widgetsTable = $this->serviceManager->get('System\Model\WidgetTable');
	
		$resultSet = array();
	
		$widgets = $widgetsTable->getEnabledWidgets();
		foreach($widgets as $widget) {
			$resultSet[] = (array) $widget;
		}
	
		return $resultSet;
	
	}
	
	public function getWidgetByName($name)
	{
		return $this->serviceManager->get('System\Model\WidgetTable')->getWidgetByName($name);
	}
	
	public function getWidgetsByNamespace($namespace)
	{
	
		if(strpos($namespace, '\\')>-1)
			list($namespace, $class) = explode('\\', $namespace);
	
		return $this->serviceManager->get('System\Model\WidgetTable')->getWidgetsByNamespace($namespace);
	
	}
	
	public function saveWidgetOption($id, $value)
	{
		return $this->serviceManager->get('System\Model\WidgetTable')->saveWidgetOption($id, $value);
	}
	
	public function saveWidgetPosition($id, $position)
	{
		return $this->serviceManager->get('System\Model\WidgetTable')->saveWidgetPosition($id, $position);
	}
	
	private function _convertHexToRgb($hex) {
		
		$hex = (string) $hex;
		$hex = str_replace("#", "", $hex);
	
		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		
		return array($r, $g, $b);
		
	}
	
	private function _convertDecToHex($r, $g, $b) {
	
		$r = dechex($r);
		$g = dechex($g);
		$b = dechex($b);
		
		if(strlen($r)<2)
			$r = str_pad($r, 2, "0", STR_PAD_LEFT);
		if(strlen($g)<2)
			$g = str_pad($g, 2, "0", STR_PAD_LEFT);
		if(strlen($b)<2)
			$b = str_pad($b, 2, "0", STR_PAD_LEFT);
		
		return array($r, $g, $b);
	
	}
	
	public function getColorScheme() {
		
		$setting = $this->getSettingByName('system_theme_color');
		$rgb = $this->_convertHexToRgb($setting->value);
		
		$dark = $setting->value;
		$medium = $this->_convertDecToHex(round($rgb[0]+round((255-$rgb[0])/18)),  
										  round($rgb[1]+round((255-$rgb[1])/18)),  
										  round($rgb[2]+round((255-$rgb[2])/18)));
		$light = $this->_convertDecToHex(round($rgb[0]+round((255-$rgb[0])/5)),  
										 round($rgb[1]+round((255-$rgb[1])/5)),  
										 round($rgb[2]+round((255-$rgb[2])/5)));
		
		return array('dark' => $dark, 
					 'medium' => $medium[0] . $medium[1] . $medium[2], 
					 'light' => $light[0] . $light[1]. $light[2]);
		
	}
	
	public function getModules($visible = true)
	{
	
		$modulesTable = $this->serviceManager->get('System\Model\SystemModuleTable');
	
		$resultSet = array();
	
		//print '<pre>';
		$modules = $modulesTable->getModules($visible);
		foreach($modules as $module) {
			$resultSet[] = (array) $module;
		}
		//print_r($resultSet);
	
		return $resultSet;
	
	}
	
	
	
	
    
}
