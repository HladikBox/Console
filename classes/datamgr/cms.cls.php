<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class CmsMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new CmsMgr();
	}

	private function __construct() {
		
	}
	
	public  function __destruct ()
	{
		
	}

    public function getMenu($login,$alias){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);

    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
    $path=$folder."menu.xml";
    $fp = fopen($path,"r");
    $str = fread($fp,filesize($path));
    $xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
    $menu = json_decode(json_encode($xmlstring),true); 

    if($menu["mainmenus"]["mainmenu"][0]==""&&$menu["mainmenus"]["mainmenu"]["name"]!=""){
      $temp=$menu["mainmenus"]["mainmenu"];
      $menu["mainmenus"]["mainmenu"]=array();
      $menu["mainmenus"]["mainmenu"][]=$temp;
    }

    for ($i=0; $i < count($menu["mainmenus"]["mainmenu"]); $i++) { 
        if($menu["mainmenus"]["mainmenu"][$i]["submenus"]["submenu"][0]==""&&$menu["mainmenus"]["mainmenu"][$i]["submenus"]["submenu"]["model"]!=""){
          $temp=$menu["mainmenus"]["mainmenu"][$i]["submenus"]["submenu"];
          $menu["mainmenus"]["mainmenu"][$i]["submenus"]["submenu"]=array();
          $menu["mainmenus"]["mainmenu"][$i]["submenus"]["submenu"][]=$temp;
        }
    }
    $menu=setArrayNoNull($menu);
    return $menu;
  }
  
  public function submitMenu($login,$alias,$menu){
      Global $CONFIG;
        $login=parameter_filter($login);
        $alias=parameter_filter($alias);

        $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";
        $path=$folder."menu.xml";

      $data = array('total_stud' => 500);

      // creating object of SimpleXMLElement
      $xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');

      // function call to convert array to xml
      //$this->array_to_xml($model,"",$xml_data);
      foreach( $menu as $key => $value ) {
          if($key=="mainmenus"){
            $mainmenus=$menu["mainmenus"]["mainmenu"];
            $mainmenusNode= $xml_data->addChild("mainmenus");
            foreach ($mainmenus as $mainmenu) {
              $mainmenunode = $mainmenusNode ->addChild("mainmenu");
              foreach ($mainmenu as $fkey => $fvalue) {
                if($fkey=="submenus"){

                    $options=$mainmenu["submenus"]["submenu"];
                    $optionsnode = $mainmenunode->addChild("submenus");
                    foreach ($options as $option) {
                      $optionnode = $optionsnode ->addChild("submenu");
                      foreach ($option as $fkey => $fvalue) {
                        $optionnode->addChild($fkey,htmlspecialchars($fvalue));
                      }
                    }

                }else {
                  $mainmenunode->addChild($fkey,htmlspecialchars($fvalue));
                }
              }
            }
          }
      }

      //saving generated xml file; 
      //echo $path;

      $result = $xml_data->asXML($path);
      return outResult(0,"SAVE SUCCESS","");
  }

  function addChild(&$node,$key,$value){
            if(trim($value)==""){
                $node->addChild($key);
            }else{
                $node->addChild($key,htmlspecialchars($value));
            }
    }

  
 }
 
 $cmsMgr=CmsMgr::getInstance();
 $cmsMgr->dbmgr=$dbmgr;
 
 
 
 
?>