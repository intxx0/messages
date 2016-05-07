<?php
namespace Message\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use User\Model\Permission;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class MessageTable
{
    protected $tableGateway;
    protected $serviceManager;

    public function __construct(TableGateway $tableGateway, $serviceManager)
    {
        $this->tableGateway = $tableGateway;
        $this->serviceManager = $serviceManager;
    }

    public function fetchAll()
    {
    	 
		$resultSet = $this->tableGateway->select();
		    		 
    	return $resultSet;
    }
    
    public function getMessages()
    {
        
        $resultSet = $this->tableGateway->select(function(Select $select) {
        	$select->columns(array(
        	                   '*', 
        	                   'date' => new \Zend\Db\Sql\Expression("DATE_FORMAT(tbl_messages.date, '%d/%m/%Y')"), 
        	                   'time' => new \Zend\Db\Sql\Expression("DATE_FORMAT(tbl_messages.date, '%H:%i:%s')"), 
        	));
        	$select->join(array('t2' => 'tbl_users'), 'tbl_messages.id_user = t2.id', array('user' => 'name'), Select::JOIN_LEFT);
        	$select->order("date DESC");
        });
        
    	return $resultSet;
    }

    public function getMessage($id)
    {
        $id  = (int) $id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row) return array();
       
        return $row;
    }
    
    public function saveMessage(Message $message)
    {
        $data = array(
            'id_user'	=> $message->id_user,
       		'message'	=> $message->message,
            'date'		=> new \Zend\Db\Sql\Expression("NOW()"),
        );
        
        $id = (int) $message->id;
        
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getMessage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }

        return ($result?$result:null);
        
    }

    public function deleteMessage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}

