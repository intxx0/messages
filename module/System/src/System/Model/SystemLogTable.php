<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class SystemLogTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /*public function fetchAll()
    {
    	
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*', new \Zend\Db\Sql\Expression("DATE_FORMAT(seg_modules_logs.date, '%d/%m/%Y %H:%i:%s') AS date")));
    		$select->join(array('t2' => 'tbl_users'), 'seg_modules_logs.id_user = t2.id', array('user' => 'name'), Select::JOIN_LEFT);
    		$select->join(array('t3' => 'tbl_modules'), 'seg_modules_logs.id_module = t3.id', array('module' => 'name'), Select::JOIN_LEFT);
    		$select->order("seg_modules_logs.date DESC");
    	});
    	
    	
        return $resultSet;
    }*/
    
    public function fetchAll($paginated = false)
    {
    	 
    	if ($paginated) {
    		$select = new Select();
    		$select->from('seg_modules_logs');
    		$select->columns(array('*', new \Zend\Db\Sql\Expression("DATE_FORMAT(seg_modules_logs.date, '%d/%m/%Y %H:%i:%s') AS date_time")));
    		$select->join(array('t2' => 'tbl_users'), 'seg_modules_logs.id_user = t2.id', array('user' => 'name'), Select::JOIN_LEFT);
    		$select->join(array('t3' => 'tbl_modules'), 'seg_modules_logs.id_module = t3.id', array('module' => 'name'), Select::JOIN_LEFT);
    		$select->order("seg_modules_logs.date DESC");
    		$resultSetPrototype = new ResultSet();
    		//$resultSetPrototype->setArrayObjectPrototype(new SystemLog());
    		$paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
    		$paginator = new Paginator($paginatorAdapter);
    		return $paginator;
    	}
    
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*', new \Zend\Db\Sql\Expression("DATE_FORMAT(seg_modules_logs.date, '%d/%m/%Y %H:%i:%s') AS date")));
    		$select->join(array('t2' => 'tbl_users'), 'seg_modules_logs.id_user = t2.id', array('user' => 'name'), Select::JOIN_LEFT);
    		$select->join(array('t3' => 'tbl_modules'), 'seg_modules_logs.id_module = t3.id', array('module' => 'name'), Select::JOIN_LEFT);
    		$select->order("seg_modules_logs.date DESC");
    	});
    	    		 
    		return $resultSet;
    }
    
    public function getSystemLogs()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }

    public function getSystemLog($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveSystemLog(SystemLog $log)
    {
        $data = array(
       		'id_module' => $log->id_module,
            'id_user' 	=> $log->id_user,
        	'date'		=> new \Zend\Db\Sql\Expression("NOW()"),
       		'message'  		=> $log->message,
        );

        $id = (int)$log->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getSystemLog($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteSystemLogs()
    {
        $this->tableGateway->delete();
    }
    
}

