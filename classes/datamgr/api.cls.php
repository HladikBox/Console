<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class ApiMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new ApiMgr();
	}

	private function __construct() {
		
	}
	
	public  function __destruct ()
	{
		
	}

	public function getApiList($login,$alias,$modellist){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
		$ret=array();
		foreach ($modellist as $key => $model) {
			if($model["nolist"]){
				$api=$this->setApi("model",$model["modelname"],"update","<b style='color:blue'>更新".$model["name"]."</b>");
				$ret["model_".$model["modelname"]."_"."update"]=$api;
			}else{
				$api=$this->setApi("model",$model["modelname"],"list","获取<b style='color:blue'>".$model["name"]."列表</b>，传入对应的搜索条件");
				$ret["model_".$model["modelname"]."_"."list"]=$api;

				$api=$this->setApi("model",$model["modelname"],"get","获取<b style='color:blue'>".$model["name"]."详情</b>, 传入对应的id");
				$var["id"]="1";
				$api["modelinput"]=json_encode($var);
				$ret["model_".$model["modelname"]."_"."get"]=$api;


				$api=$this->setApi("model",$model["modelname"],"update","<b style='color:blue'>更新".$model["name"]."</b>，传入对应的表字段");
				$ret["model_".$model["modelname"]."_"."update"]=$api;

				$api=$this->setApi("model",$model["modelname"],"delete","<b style='color:blue'>删除".$model["name"]."</b>的条目，传入idlist=1,2,3,4,5");
				$ret["model_".$model["modelname"]."_"."delete"]=$api;
			}
		}

		$folder=$CONFIG['workspace']['path']."\\$login\\$alias\\api\\";
		$model_folder=scandir($folder);
		for($i=2;$i<count($model_folder);$i++){
			$model=$model_folder[$i];
			if(is_dir($folder.$model)){
				$funclist=scandir($folder.$model);
				for($j=2;$j<count($funclist);$j++){
					$filenamearr=explode(".", $funclist[$j]);
					if(count($filenamearr)==2&&$filenamearr[1]=="php"){
						$func=$filenamearr[0];
						$funcmd=$folder.$model."\\".$func.".md";
						if(file_exists($funcmd)){
							$desc=file_get_contents($funcmd);
							$desc = str_replace("\r\n","<br />",$desc);
						}
						$api=$this->setApi("self",$model,$func,$desc);
						$ret["self_".$model."_".$func]=$api;
					}
				}
			}
		}

		$path=$CONFIG['workspace']['path']."\\$login\\$alias\\api.xml";
		$fp = fopen($path,"r");
	    $str = fread($fp,filesize($path));

	    $xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
	    $aplconfig = json_decode(json_encode($xmlstring),true); 

	    if($aplconfig["apis"]["api"][0]==""&&$aplconfig["apis"]["api"]["type"]!=""){
	      $temp=$aplconfig["apis"]["api"];
	      $aplconfig["apis"]["api"]=array();
	      $aplconfig["apis"]["api"][]=$temp;
	    }

	    foreach ($aplconfig["apis"]["api"] as $key => $value) {
	    	$type=$value["type"];
	    	$model=$value["model"];
	    	$func=$value["func"];
	    	$active=$value["active"];
	    	$output=$value["output"];
	    	$input=$value["input"];
	    	if(isset($ret["$type"."_"."$model"."_"."$func"])){
	    		$ret["$type"."_"."$model"."_"."$func"]["active"]=$active;
	    		$ret["$type"."_"."$model"."_"."$func"]["output"]=$output;
	    		$ret["$type"."_"."$model"."_"."$func"]["input"]=$input;
	    	}
	    }
        $ret=setArrayNoNull($ret);
		return $ret;
	}
	public function setApi($type,$model,$func,$desc=""){
		$ret["type"]=$type;
		$ret["model"]=$model;
		$ret["func"]=$func;
		$ret["description"]=$desc;
		return $ret;
	}
    public function save($login,$alias,$apis){
     Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\api.xml";

      $data = array('total_stud' => 500);

      // creating object of SimpleXMLElement
      $xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');

      
      $optionsnode = $xml_data->addChild("apis");
      foreach ($apis as $option) {
      $optionnode = $optionsnode ->addChild("api");
            $type=$option["type"];
            $model=$option["model"];
            $func=$option["func"];
            $description=$option["description"];
            if($type=="self"){
                $apipath=$CONFIG['workspace']['path']."\\$login\\$alias\\api\\$model\\";
                $apifile=$apipath.$func.".php";
                $md=$apipath.$func.".md";
                if(is_dir($apipath)){
                    mkdir($apipath,0777,true);
                }
                if(!file_exists($apifile)){
                    copy(ROOT."\\workspace_copy\\api.php",$apifile);
                }

                $mdf = fopen($md, "w+");
                fwrite($mdf, $description);
                fclose($mdf);
            }

          foreach ($option as $fkey => $fvalue) {
            $optionnode->addChild($fkey,htmlspecialchars($fvalue));
          }
      }

      //saving generated xml file; 
      //echo $path;
      $result = $xml_data->asXML($path);
      return outResult(0,"保存成功","");
  }

  function addChild(&$node,$key,$value){
            if(trim($value)==""){
                $node->addChild($key);
            }else{
                $node->addChild($key,htmlspecialchars($value));
            }
    }
 }
 
 $apiMgr=ApiMgr::getInstance();
 $apiMgr->dbmgr=$dbmgr;
 
 
 
 
?>