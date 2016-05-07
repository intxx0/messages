<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class TimezoneTable
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
    
    public function getTerrainTimezones()
    {
    	 
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->where("type = '1'");
    		$select->order("name ASC");
    	});
    	 
    	return $resultSet;
    
    }
    
    public function getIslandsTimezones()
    {
    
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->where("type = '2'");
    		$select->order("name ASC");
    	});
    
    	return $resultSet;
    
    }
    
}

