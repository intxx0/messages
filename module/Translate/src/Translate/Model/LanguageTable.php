<?php
namespace Translate\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use User\Model\Permission;

use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class LanguageTable
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
    		$select->from('tbl_languages');
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
    
    public function getLanguages()
    {
    	$resultSet = $this->tableGateway->select("status != '-1'");
    	return $resultSet;
    }

    public function getLanguage($id)
    {
        $id  = (int) $id;
        
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        
        if(!$row)
            throw new \Exception("Could not find row $id");
        
        return $row;
    }
    
    public function saveLanguage(Language $language)
    {
        $data = array(
            'name' 		=> $language->name,
       		'locale'	=> $language->locale,
       		'status'  	=> $language->status,
        );
        
        $id = (int) $language->id;
        
        if ($id == 0) {
            $result = $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getLanguage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }

        return ($result?$result:null);
        
    }

    public function deleteLanguage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
    
}

