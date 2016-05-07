<?php
namespace System\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;

class WidgetTable
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
    
    public function getWidgets($visible = true)
    {
    	//$resultSet = $this->tableGateway->select();
    	
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	//$select->where("value = '1'");
    	if($visible===true)
    		$select->where("visible = '1'");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet;
    }
    
    public function getEnabledWidgets()
    {
    	
    	$select = $this->tableGateway->getSql()->select();
    	 
    	$select->columns(array('*'));
    	$select->where("value = '1'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet;
    	
    }
    
    public function getWidgetsByModule($idModule)
    {
    	
    	$select = $this->tableGateway->getSql()->select();
    	
    	$select->columns(array('*'));
    	$select->where("id_module = '{$idModule}'");
    	$select->order("section");
    	
    	$resultSet = $this->tableGateway->selectWith($select);
    	 
    	return $resultSet;
    	 
    }
    
    public function getWidgetByName($name)
    {
    	 
    	$select = $this->tableGateway->getSql()->select();
    	 
    	$select->columns(array('*'));
    	$select->where("name = '{$name}'");
    	 
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet->current();
    
    }
    
    public function getWidgetsByNamespace($namespace)
    {
    
    	$select = $this->tableGateway->getSql()->select();
    
    	$select->columns(array('*'));
    	$select->join(array('t2' => 'tbl_modules'), 'seg_modules_widgets.id_module = t2.id', array('namespace'), Select::JOIN_INNER);
    	$select->where("t2.namespace = '{$namespace}'");
    
    	$resultSet = $this->tableGateway->selectWith($select);
    
    	return $resultSet;
    
    }

    public function getWidget($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    public function saveWidget($widget)
    {
        $data = array(
       		'id_module' => $widget->id_module,
            //'id_user' => $log->id_user,
       		'section' 	=> $widget->section,
       		'name' 		=> $widget->name,
       		'label' 	=> $widget->label,
       		'description' 	=> $widget->description,
        	'options' 	=> $widget->options,
       		'service' 	=> $widget->service,
       		'visible'  	=> $widget->visible,
        );

        $id = (int)$widget->id;
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            return $result;
        } else {
            if ($this->getWidget($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    public function saveWidgetOption($id, $value)
    {
    	
    	$id = (int) $id;
    	$value = (string) $value;
    	
   		if($this->getWidget($id)) {
   			$widget = array('value' => $value);
   			$this->tableGateway->update($widget, array('id' => $id));
   		} else {
   			throw new \Exception('Form id does not exist');
   		}
   		
    }
    
    public function saveWidgetPosition($id, $position)
    {
    	 
    	$id = (int) $id;
    	$position = (string) $position;
    	 
    	if($this->getWidget($id)) {
    		$widget = array('position' => $position);
    		$this->tableGateway->update($widget, array('id' => $id));
    	} else {
    		throw new \Exception('Form id does not exist');
    	}
    	 
    }
    
    

    public function deleteWidget($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}
