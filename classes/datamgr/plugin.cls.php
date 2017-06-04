<?php

 class PluginMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new PluginMgr();
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

	public function addToPlugXml($login,$alias,$pluginId,$pluginArray){
	    Global $CONFIG;
	    $login=parameter_filter($login);
	    $alias=parameter_filter($alias);

	    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
		$path=$folder."plugin.xml";
		$fp = fopen($path,"r");
		$str = fread($fp,filesize($path));
		if(empty($str)){
			$str="<root></root>";
		}
		$xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
		$plugin = json_decode(json_encode($xmlstring),true);

		$plugin[$pluginId]=$pluginArray;

		$data = array('total_stud' => 500);
		$xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');
		foreach( $plugin as $key => $value ) {
			$node=$xml_data->addChild($key);
			foreach( $value as $ak => $av ) {
				$node->addChild($ak,$av);
			}
		}
		$result = $xml_data->asXML($path);
		return true;
	}

	public function getPluginList(){

	    $path=ROOT."/appplugins/plugins.xml";
		$fp = fopen($path,"r");
		$str = fread($fp,filesize($path));

		$xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
		$plugin = json_decode(json_encode($xmlstring),true);

		return $plugin;

	}
	public function getAppPluginList($login,$alias){
	    Global $CONFIG;
	    $login=parameter_filter($login);
	    $alias=parameter_filter($alias);

	    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
		$path=$folder."plugin.xml";
		$fp = fopen($path,"r");
		$str = fread($fp,filesize($path));

		$xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
		$plugin = json_decode(json_encode($xmlstring),true);

		return $plugin;

	}
 }
 
 $pluginMgr=PluginMgr::getInstance();
?>
