<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class SettingTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
    	
    	$resultSet = $this->tableGateway->select();
    	
        return $resultSet;
    }
    
    public function getSettings()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }
    
    public function getSettingsByModule($idModule)
    {
    	
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	$select->where("id_module = '{$idModule}'");
    	$select->order("section");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet;
    	 
    }
    
    public function getSettingByName($name)
    {
    	 
    	$select = $this->tableGateway->getSql()->select();
    	 
    	$select->columns(array('*'));
    	$select->where("name = '{$name}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet->current();
    
    }
    
    public function getSettingsByNamespace($namespace)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_modules'), 'seg_modules_settings.id_module = t2.id', array('namespace'), Select::JOIN_INNER);
    	$select->where("t2.namespace = '{$namespace}'");
    
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet;
    
    }

    public function getSetting($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveSetting($setting)
    {
        $data = array(
       		'id_module' => $setting->id_module,
            //'id_user' => $log->id_user,
       		'section' 	=> $setting->section,
       		'type' 		=> $setting->type,        		
       		'name' 		=> $setting->name,
       		'label' 	=> $setting->label,
       		'description' 	=> $setting->description,
       		'value' 	=> $setting->value,        	
        	'options' 	=> $setting->options,
       		'service' 	=> $setting->service,
       		'filter' 	=> $setting->filter,        		
       		'visible'  	=> $setting->visible,
        );

        $id = (int)$setting->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getSetting($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteSetting($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function updateImageFile($name, $file)
    {
    	$this->tableGateway->update(array('value' => $file), array('name' => $name));
    }
    
}
