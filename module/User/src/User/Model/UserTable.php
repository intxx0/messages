<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /*public function fetchAll()
    {
    	
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->join(array('t2' => 'tbl_roles'), 'tbl_users.id_role = t2.id', array('group' => 'name'), Select::JOIN_LEFT);
    		$select->where("tbl_users.status != '-1'");
    		$select->order("name ASC");
    	});
    	
        return $resultSet;
    }*/
    
    public function fetchAll($paginated = false)
    {
    	
    	if ($paginated) {
    		$select = new Select();
    		$select->from('tbl_users');
   			$select->columns(array('*'));
   			$select->join(array('t2' => 'tbl_roles'), 'tbl_users.id_role = t2.id', array('group' => 'name'), Select::JOIN_LEFT);
   			$select->where("tbl_users.status != '-1'");
   			$select->order("name ASC");
   			$resultSetPrototype = new ResultSet();
   			//$resultSetPrototype->setArrayObjectPrototype(new User());
   			$paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
   			$paginator = new Paginator($paginatorAdapter);
   			return $paginator;
    	}
    	 
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->join(array('t2' => 'tbl_roles'), 'tbl_users.id_role = t2.id', array('group' => 'name'), Select::JOIN_LEFT);
    		$select->where("tbl_users.status != '-1'");
    		$select->order("name ASC");
    	});
    		 
   		return $resultSet;
    }
    
    
    public function getUsers()
    {
    	//$resultSet = $this->tableGateway->select("status != '-1'");
    	return $resultSet;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getUserByLogin($login)
    {
    	 
    	$select = $this->tableGateway->getSql()->select();

    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_roles'), 'tbl_users.id_role = t2.id', array('group' => 'name'), Select::JOIN_LEFT);
    	$select->where("tbl_users.status != '-1'");
    	$select->where("tbl_users.login = '{$login}'");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet->current();
    }

    public function saveUser(User $user)
    {
        $data = array(
       		'id_role'	=> $user->id_role,
            'name' 		=> $user->name,
            'email'  	=> $user->email,
       		'login'  	=> $user->login,
       		'password'  => new \Zend\Db\Sql\Expression("SHA1('{$user->password}')"),
       		'description'  => $user->description,
       		'status'  	=> $user->status,
        );

        $id = (int)$user->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function getLastId()
    {
    	return $this->tableGateway->getLastInsertValue();
    }
    
    public function flushRegistries()
    {
    	return $this->tableGateway->delete(array('status' => '-1'));
    }
    
    public function updateImageFile($id, $file)
    {
    	$this->tableGateway->update(array('file' => $file), array('id' => $id));
    }
    
    public function getUserHash($id)
    {
    	$id = (int) $id;
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('uid' => new \Zend\Db\Sql\Expression("SHA1(CONCAT_WS(tbl_users.id,tbl_users.login,tbl_users.password))")));
    	$select->where("tbl_users.id = '{$id}'");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	$row = $resultSet->current();
    
    	return $row->uid;
    }
    
    public function getUserByHash($uid)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->where("SHA1(CONCAT_WS(tbl_users.id,tbl_users.login,tbl_users.password)) = '{$uid}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    	$row = $resultSet->current();
    
    	return $row;
    }
    
}
