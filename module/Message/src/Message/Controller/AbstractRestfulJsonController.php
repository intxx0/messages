<?php
/**
 * File for Messages Controller Class
 *
 * @category  Messages
 * @package   Messages_Controller
 * @author    Osvaldo Souza <osvaldo@extendtecnologia.com.br>
 * @copyright Copyright (c) 2016  Extend Tecnologia
 */

namespace Message\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\Http\Response;

class AbstractRestfulJsonController extends AbstractRestfulController
{
    
    protected function methodNotAllowed()
    {
        $this->response->setStatusCode(405);
        throw new \Exception('Method Not Allowed');
    }
    
    public function create($data)
    {
        return $this->methodNotAllowed();
    }
    
    public function delete($id)
    {
        return $this->methodNotAllowed();
    }
    
    public function deleteList()
    {
        return $this->methodNotAllowed();
    }
    
    public function get($id)
    {
        return $this->methodNotAllowed();
    }
    
    public function getList()
    {
        return $this->methodNotAllowed();
    }
    
    public function head($id = null)
    {
        return $this->methodNotAllowed();
    }
    
    public function options()
    {
        return $this->methodNotAllowed();
    }
    
    public function patch($id, $data)
    {
        return $this->methodNotAllowed();
    }
    
    public function replaceList($data)
    {
        return $this->methodNotAllowed();
    }
    
    public function patchList($data)
    {
        return $this->methodNotAllowed();
    }
    
    public function update($id, $data)
    {
        return $this->methodNotAllowed();
    }
    
}
