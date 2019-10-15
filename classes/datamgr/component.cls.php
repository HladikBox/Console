<?php

 class ComponentMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new ComponentMgr();
	}

	private function __construct() {
		
		//$id=parameter_filter($user["id"]);
        //$query = $this->dbmgr->query($sql);
        //$result = $this->dbmgr->fetch_array_all($query);
        //return $result;

	}
	
	public  function __destruct ()
	{
		
	}


	public function getComponentList(){

	    $path=ROOT."/appcomponents/components.xml";
		$fp = fopen($path,"r");
		$str = fread($fp,filesize($path));

		$xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
		$plugin = json_decode(json_encode($xmlstring),true);

		return $plugin;

	}

 }
 
 $componentMgr=ComponentMgr::getInstance();
?>
