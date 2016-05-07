<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class MimeTable
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
    
    public function getMimes()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }
    
    public function getMimeByName($name)
    {
    	 
    	$select = $this->tableGateway->getSql()->select();
    	 
    	$select->columns(array('*'));
    	$select->where("name = '{$name}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet->current();
    
    }
    
    public function getExtensionByType($type)
    {

    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	$select->where("mime LIKE '%{$type}%'");
    	
    	//print $select->getSqlString(); exit();
    	
    	//$resultSet = $this->tableGateway->selectWith($select);
    	
    	// Note: Prevent MySQL Error 1064 with some double quoted queries
    	$backtrickQuoted = $this->tableGateway->getSql()->getSqlStringForSqlObject($select);
    	$resultSet = $this->tableGateway->getAdapter()->query($backtrickQuoted, \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE)->toArray();
    	
    	//return $resultSet->current();
    	return $resultSet[0]['extension'];
    	 
    }

    public function getMime($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
}
