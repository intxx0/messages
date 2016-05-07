<?php
namespace User\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class ResourceTable
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
    		$select->join(array('t2' => 'tbl_modules'), 'seg_modules_resources.id_module = t2.id', array('module' => 'name'), Select::JOIN_INNER);
    		$select->order("id ASC");
    	});
    	
        return $resultSet;
        
    }
    
    public function getResources()
    {
    	
    	$resultSet = $this->tableGateway->select(function(Select $select) {
    		$select->columns(array('*'));
    		$select->join(array('t2' => 'tbl_modules'), 'seg_modules_resources.id_module = t2.id', array('module' => 'name'), Select::JOIN_INNER);
    		$select->where("visible = '1'");
    		$select->order("id ASC");
    	});
    	
        return $resultSet;
        
    }

    public function getResource($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function getResourcesByModule($idModule)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->where("id_module = '{$idModule}'");
    	$select->where("visible = '1'");
    
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet;
    	 
    }
    
    public function getResourceByRoute($route)
    {
    	
    	$controller = $route->getParam('controller');
    	
    	$module		= substr($controller, 0, strpos($controller, '\\'));
    	$controller = strtolower(end(explode('\\', $controller)));
    	$action		= $route->getParam('action');
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_modules'), 'seg_modules_resources.id_module = t2.id', array('module' => 'namespace'), Select::JOIN_INNER);
    	$select->where("t2.namespace = '{$module}'");
    	$select->where("controller = '{$controller}'");
    	$select->where("action = '{$action}'");
    	$select->where("restricted = '1'");
    
    	$resultSet = $this->tableGateway->selectWith($select);
    	
    	//print_r($resultSet); exit();
    
    	return $resultSet->current();
    
    }
    
    public function getInvisibleDefaults()
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->where("`default` = '1'");
    	$select->where("visible = '0'");
    
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet;
    
    }
    
    public function saveResource(Resource $resource)
    {
        $data = array(
            'name' 		=> $resource->name,
       		'status'  	=> $resource->status,
        );

        $id = (int)$resource->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getResource($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteResource($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}

