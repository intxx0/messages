<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class PermissionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
    	
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->join(array('t2' => 'tbl_roles'), 'rel_permissions.id_role = t2.id', array('group' => 'name'), Select::JOIN_INNER);
    		$select->join(array('t3' => 'seg_modules_resources'), 'rel_permissions.id_resource = t2.id', array('resource' => 'name'), Select::JOIN_INNER);
    		$select->join(array('t4' => 'tbl_modules'), 't3.id_module = t4.id', array('module' => 'name'), Select::JOIN_INNER);
    		$select->order("id ASC");
    	});
    	
        return $resultSet;
        
    }
    
    public function getPermissions($idRole = null)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_roles'), 'rel_permissions.id_role = t2.id', array('group' => 'name'), Select::JOIN_INNER);
    	$select->join(array('t3' => 'seg_modules_resources'), 'rel_permissions.id_resource = t3.id', array('resource' => 'name'), Select::JOIN_INNER);
    	
    	if($idRole)
    		$select->where("rel_permissions.id_role = '{$idRole}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    	
    	return $resultSet;
    	
    }
    
    public function getPermission($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getPermissionByRole($idRole, $idResource)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_roles'), 'rel_permissions.id_role = t2.id', array('group' => 'name'), Select::JOIN_INNER);
    	$select->join(array('t3' => 'seg_modules_resources'), 'rel_permissions.id_resource = t3.id', array('resource' => 'name'), Select::JOIN_INNER);
    	$select->where("rel_permissions.id_role = '{$idRole}'");
    	$select->where("rel_permissions.id_resource = '{$idResource}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    	
    	return $resultSet->current();
    	
    }
    
    public function savePermission(Permission $permission)
    {
        $data = array(
            'id_role' 		=> $permission->id_role,
       		'id_resource'  	=> $permission->id_resource,
        );

        $id = (int)$permission->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getPermission($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePermission($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
    public function deletePermissionByRole($idRole, $idResource)
    {
    	$this->tableGateway->delete(array('id_role' => $idRole, 'id_resource' => $idResource));
    }
    
}
