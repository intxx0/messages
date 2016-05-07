<?php
namespace Translate\Model;

use Zend\Paginator\Adapter\ArrayAdapter, 
	Zend\Paginator\Paginator, 
	Translate\Model\Translate;

class TranslateFile
{
	protected $serviceManager;
	
	private $_locale = null;
	private $_translations = array();
	
    public function __construct($serviceManager)
    {
    	
    	$this->serviceManager = $serviceManager;
    	
    }
    
    private function _loadTranslateFile($overload = false, $search = null)
    {
    	
    	if ($overload&&count($this->_translations)>0)
    		return;
    	
    	$resultSet = include "./data/translate/{$this->_locale}.php";
    	
    	$i = 0;
    	$this->_translations = array();
    	
    	foreach ($resultSet as $key => $value) {
    		if (($search===null)||(strstr($key, $search)||strstr($value, $search))) {
	    		$row = new Translate();
	    		$row->id = $i;
	    		$row->from = $key;
	    		$row->to = $value;
	    		$this->_translations[$i] = $row;
    		}
    		$i++;
    	}
    	
    }
    
    private function _saveTranslateFile()
    {
    	
    	$content = "<?php\nreturn array(\n";
    	
    	foreach ($this->_translations as $translation) {
    		if ($translation!==null)
    			$content .= "'" . addslashes($translation->from) . "' => '" . addslashes($translation->to) . "',\n";
    	}
    	
    	$content .= ");\n?>\n";
    	
    	file_put_contents("./data/translate/{$this->_locale}.php", $content);
    	
    }

    public function getTranslations($locale, $paginated = false, $search = null)
    {
    	
    	$this->_locale = $locale;
    	$this->_loadTranslateFile(false, $search);
    	
    	if ($paginated) {
    		$paginatorAdapter = new ArrayAdapter($this->_translations);
    		$paginator = new Paginator($paginatorAdapter);
    		return $paginator;
    	}
    
    	return $this->_translations;
    	
    }
    
    public function getTranslation($locale, $id)
    {
    	
        $id  = (int) $id;
        
        $this->_locale = $locale;
        $this->_loadTranslateFile();
        
        return $this->_translations[$id];
        
    }
    
    public function saveTranslation($locale, Translate $translate)
    {
    	
    	$this->_locale = $locale;
    	$this->_loadTranslateFile();
    	
        if ($translate->id===null) {
        	$this->_translations[] = $translate;
        } else {
        	$this->_translations[$translate->id] = $translate;
        }
        
        $this->_saveTranslateFile();
        
    }

    public function deleteTranslation($locale, $id)
    {
    	
    	$this->_locale = $locale;
    	$this->_loadTranslateFile();
    	
    	$this->_translations[$id] = null;
    	$this->_saveTranslateFile();
    	
    }
    
}

