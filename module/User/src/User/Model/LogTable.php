<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class LogTable
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
    
    public function getLogs()
    {
    	$resultSet = $this->tableGateway->select();
    	return $resultSet;
    }

    public function getLog($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveLog(Log $log)
    {
        $data = array(
            'id_user' 	=> $log->id_user,
        	'date'		=> new \Zend\Db\Sql\Expression("NOW()"),
       		'ip'  		=> $log->ip,
        );

        $id = (int)$log->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getLog($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteLog($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function getLastAccessLogs()
    {
    	 
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*', 'date' => new \Zend\Db\Sql\Expression("DATE_FORMAT(seg_user_access.date, '%d/%m/%Y %H:%i:%s')")));
    		$select->join(array('t2' => 'tbl_users'), 'seg_user_access.id_user = t2.id', array('user' => 'name'), Select::JOIN_LEFT);
    		$select->join(array('t3' => 'tbl_roles'), 't2.id_role = t3.id', array('group' => 'name'), Select::JOIN_LEFT);
    		$select->order("id DESC");
    		$select->limit(8);
    	});
    	 
    	return $resultSet;
    }
    
}

