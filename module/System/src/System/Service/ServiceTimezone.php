<?php
/**
 * Timezone settings provider
 *
 * @category  Service
 * @package   Service_System
 * @author    Osvaldo Souza
 * @copyright Copyright (c) 2013  Extend Tecnologia
 */

namespace System\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use System\Model\SystemLog;

class ServiceTimezone implements ServiceLocatorAwareInterface
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
	
	protected function convertXY($latitude, $longitude, $mapWidth, $mapHeight)
	{
		$x = round(($longitude + 180) * ($mapWidth / 360));
		$y = round((($latitude * -1) + 90) * ($mapHeight / 180));
		return array($x, $y);
	}
	
	public function getTimezones()
	{
		return $this->serviceManager->get('System\Model\TimezoneTable')->fetchAll();
	}
	
	public function getTimezonesPolygons($mapWidth, $mapHeight)
	{
		
		$timezones = array();
		
		$empty_date = @date_create('2012-01-01T00:00:00Z');
		
		$resultSet = $this->serviceManager->get('System\Model\TimezoneTable')->getTerrainTimezones();
		
		// Parse the main timezone export file.
		foreach ($resultSet as $result) {
			
			$timezone_name = $result->name;
			$timezone_data = $result->polygon;
		
			// Determine offset for grouping.
			if (!isset($timezones[$timezone_name])) {
				// Not all timezones may be in every version of PHP.
				if ($timezone = @timezone_open($timezone_name)) {
					$timezone_location = timezone_location_get($timezone);
					$timezones[$timezone_name]['offset'] = round(timezone_offset_get($timezone, $empty_date) / 3600, 1);
					$timezones[$timezone_name]['pin'] = $this->convertXY($timezone_location['latitude'], $timezone_location['longitude'], $mapWidth, $mapHeight);
					$timezones[$timezone_name]['country'] = $timezone_location['country_code'] !== '??' ? $timezone_location['country_code'] : NULL;
				}
				else {
					$timezones[$timezone_name]['offset'] = NULL;
					$timezones[$timezone_name]['pin'] = array();
					$timezones[$timezone_name]['country'] = NULL;
				}
				$timezones[$timezone_name]['polys'] = array();
				$timezones[$timezone_name]['rects'] = array();
			}
		
			// Remove MULTIPOLYGON() from the data.
			$timezone_data = substr($timezone_data, 13);
			$timezone_data = substr($timezone_data, 0, strlen($timezone_data) - 1);
		
			$polys = explode(')),((', $timezone_data);
		
			foreach ($polys as $poly) {
				// Remove leading or trailing parethesis.
				$poly = trim($poly, ')(');
		
				// Most of our polygons will only have an outer polygon, though its possible
				// that we need to cutout an inner polygon. Since ImageMaps don't support
				// this, we discard the cutout.
				list($outer_poly) = explode('),(', $poly);
		
				$area_poly = array();
				$longlats = explode(',', $outer_poly);
				foreach ($longlats as $longlat) {
					@list($longitude, $latitude) = explode(' ', $longlat);
					list($x, $y) = $this->convertXY($latitude, $longitude, $mapWidth, $mapHeight);
					$area_poly[] = $x;
					$area_poly[] = $y;
				}
				$timezones[$timezone_name]['polys'][] = $area_poly;
			}
		}
		
		// Optionally make islands easier to select by using bounding boxes.
		
		$resultSet = $this->serviceManager->get('System\Model\TimezoneTable')->getIslandsTimezones();
		
		foreach ($resultSet as $result) {
			
			$timezone_name = $result->name;
			$timezone_data = $result->polygon;
		
			// Don't allow wrapping across the seam of the map.
			if ($timezone_name === 'Pacific/Fiji' || $timezone_name === 'Pacific/Auckland') {
				continue;
			}
		
			// Remove BOX() from the data.
			$timezone_data = substr($timezone_data, 4);
			$timezone_data = substr($timezone_data, 0, strlen($timezone_data) - 1);
		
			$area_poly = array();
			$longlats = explode(',', trim($timezone_data, ')('));
		
			@list($longitude, $latitude) = explode(' ', $longlats[0]);
			list($x1, $y1) = $this->convertXY($latitude, $longitude, $mapWidth, $mapHeight);
			@list($longitude, $latitude) = explode(' ', $longlats[1]);
			list($x2, $y2) = $this->convertXY($latitude, $longitude, $mapWidth, $mapHeight);
		
			// Ensure minimum areas.
			if ($x2 - $x1 < 10) {
				$x1 -= 5;
				$x2 += 5;
			}
			if ($y1 - $y2 < 10) {
				$y2 -= 5;
				$y1 += 5;
			}
		
			if (isset($timezones[$timezone_name])) {
				$timezones[$timezone_name]['rects'] = array(array($x1, $y1, $x2, $y2));
				if (count($timezones[$timezone_name]['polys']) === 1) {
					$timezones[$timezone_name]['polys'] = array();
				}
			}
		}
		
		return $timezones;
		
	}
    
}

