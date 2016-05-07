<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class StorageTable
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
    
    public function getStorages()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }
    
    public function getStoragesByModule($idModule)
    {
    	
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	$select->where("id_module = '{$idModule}'");
    	$select->order("section");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet;
    	 
    }
    
    public function getStorageByName($name)
    {
    	 
    	$select = $this->tableGateway->getSql()->select();
    	 
    	$select->columns(array('*'));
    	$select->where("name = '{$name}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    	
    	return $resultSet->current();
    
    }
    
    public function getStorageTypesByName($name)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('storage' => 'name'));
    	$select->join(array('t2' => 'rel_storages_mime_types'), 'seg_modules_storages.id = t2.id_storage', array(), Select::JOIN_INNER);
    	$select->join(array('t3' => 'tbl_mime_types'), 't2.id_mime_type = t3.id', array('mime', 'extension'), Select::JOIN_INNER);
    	$select->where("seg_modules_storages.name = '{$name}'");
    	$select->group(array('t2.id'));
    	
    	//$resultSet = $this->tableGateway->selectWith($select);
    	
    	// Note: Prevent MySQL Error 1064 with some double quoted queries
    	$backtrickQuoted = $this->tableGateway->getSql()->getSqlStringForSqlObject($select);
    	
    	//print $backtrickQuoted; exit();
    	
    	$resultSet = $this->tableGateway->getAdapter()->query($backtrickQuoted, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);
    
    	return $resultSet;
    
    }

    public function getStorage($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveStorage($Storage)
    {
        $data = array(
       		'id_module' => $Storage->id_module,
       		'name' 		=> $Storage->name,
       		'path' 		=> $Storage->path,
       		'mime_type' => $Storage->mime_type,
        	'options' 	=> $Storage->options,
        );

        $id = (int)$Storage->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getStorage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteStorage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
