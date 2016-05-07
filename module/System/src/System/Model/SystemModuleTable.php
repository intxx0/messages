<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class SystemModuleTable
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
    		$select->where("visible = '1'");
    		$select->order("id ASC");
    	});
    	
        return $resultSet;
        
    }
    
    public function getModules($visible = true)
    {
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	//$select->where("value = '1'");
    	if($visible===true)
    		$select->where("visible = '1'");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet;
    }

    public function getModule($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveModule(Module $module)
    {
        $data = array(
            'name' 		=> $module->name,
       		'status'  	=> $module->status,
        );

        $id = (int)$module->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getModule($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteModule($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}

