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
		$ret=array();
		foreach ($modellist as $key => $model) {
			if($model["nolist"]){
				$api=$this->setApi("model",$model["modelname"],"update","<b style='color:blue'>更新".$model["name"]."</b>");
				$ret["model_".$model["modelname"]."_"."update"]=$api;
			}else{
				$api=$this->setApi("model",$model["modelname"],"list","获取<b style='color:blue'>".$model["name"]."列表</b>，传入对应的搜索条件");
				$ret["model_".$model["modelname"]."_"."list"]=$api;

				$api=$this->setApi("model",$model["modelname"],"get","获取<b style='color:blue'>".$model["name"]."详情</b>, 传入对应的id");
				$ret["model_".$model["modelname"]."_"."get"]=$api;

				$api=$this->setApi("model",$model["modelname"],"update","<b style='color:blue'>更新".$model["name"]."</b>，传入对应的表字段");
				$ret["model_".$model["modelname"]."_"."update"]=$api;

				$api=$this->setApi("model",$model["modelname"],"delete","<b style='color:blue'>删除".$model["name"]."</b>的条目，传入id=1,2,3,4,5");
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

		return $ret;
	}
	public function setApi($type,$model,$func,$desc=""){
		$ret["type"]=$type;
		$ret["model"]=$model;
		$ret["func"]=$func;
		$ret["description"]=$desc;
		return $ret;
	}
 }
 
 $apiMgr=ApiMgr::getInstance();
 $apiMgr->dbmgr=$dbmgr;
 
 
 
 
?>