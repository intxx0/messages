<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use User\Model\Permission;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class RoleTable
{
    protected $tableGateway;
    protected $serviceManager;

    public function __construct(TableGateway $tableGateway, $serviceManager)
    {
        $this->tableGateway = $tableGateway;
        $this->serviceManager = $serviceManager;
    }

    /*public function fetchAll()
    {
    	
    	$resultSet = $this->tableGateway->select("status != '-1'");
    	
        return $resultSet;
    }*/
    
    public function fetchAll($paginated = false)
    {
    	 
    	if ($paginated) {
    		$select = new Select();
    		$select->from('tbl_roles');
    		$select->columns(array('*'));
    		$resultSetPrototype = new ResultSet();
    		//$resultSetPrototype->setArrayObjectPrototype(new User());
    		$paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
    		$paginator = new Paginator($paginatorAdapter);
    		return $paginator;
    	}
    
		$resultSet = $this->tableGateway->select("status != '-1'");
		    		 
    	return $resultSet;
    }
    
    public function getRoles()
    {
    	$resultSet = $this->tableGateway->select("status != '-1'");
    	return $resultSet;
    }

    public function getRole($id)
    {
        $id  = (int) $id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row)
            throw new \Exception("Could not find row $id");
        
        $resultSet = $this->serviceManager->get('User\Model\ModuleTable')->fetchAll();
        
        foreach($resultSet->toArray() as $result) {
        	$resources = $this->serviceManager->get('User\Model\ResourceTable')->getResourcesByModule($result['id']);
        	foreach($resources as $resource) {
        		$permission = $this->serviceManager->get('User\Model\PermissionTable')->getPermissionByRole($id, $resource->id);
        		if($permission) {
        			$row->fieldsets[$result['name']]['resource-' . $resource['id']] = '1';
        		}
        	}
        }
        
        return $row;
    }
    
    public function saveRole(Role $role)
    {
        $data = array(
            'name' 		=> $role->name,
       		'status'  	=> $role->status,
        );
        
        $resourceTable = $this->serviceManager->get('User\Model\ResourceTable');
        $permissionTable = $this->serviceManager->get('User\Model\PermissionTable');

        $id = (int) $role->id;
        
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
            $resources = $resourceTable->getInvisibleDefaults();
            foreach($resources as $resource) {
            	$permission = new Permission;
            	$permission->id_role = $id;
            	$permission->id_resource = $resource['id'];
            	$permissionTable->savePermission($permission);
            }
            	
        } else {
            if ($this->getRole($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }

        $resultSet = $this->serviceManager->get('User\Model\ModuleTable')->fetchAll();
        
        foreach($resultSet->toArray() as $result) {
        	$resources = $this->serviceManager->get('User\Model\ResourceTable')->getResourcesByModule($result['id']);
        	foreach($resources as $resource) {
        		$permissionTable = $this->serviceManager->get('User\Model\PermissionTable');
        		$permission = $permissionTable->getPermissionByRole($id, $resource->id);
        		if(isset($role->fieldsets[$result['name']]['resource-' . $resource->id])) {
        			if(!$permission) {
		       			$permission = new Permission;
		       			$permission->id_role = $id;
		       			$permission->id_resource = $resource->id;
		       			$permissionTable->savePermission($permission);
	        		}
        		} else {
        			if(isset($permission->id)) {
        				$permissionTable->deletePermission($permission->id);
        			}
        		}
        	}
        }
        
        return ($result?$result:null);
        
    }

    public function deleteRole($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}

