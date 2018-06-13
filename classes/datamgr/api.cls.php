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

    public function getOutApiList($login,$alias){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
		$ret=array();
		
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
	    //print_r($aplconfig);
	    foreach ($aplconfig["apis"]["api"] as $key => $value) {
	    	$model=$value["model"];
	    	$ret[$model][]=$value;
	    }
        $ret=setArrayNoNull($ret);
        $ret=bubbleSort($ret,"func");
        $ret=bubbleSort($ret,"model");
		return $ret;
	}
	public function getApiList($login,$alias,$modellist){
		Global $CONFIG;
        $login=parameter_filter($login);
        $alias=parameter_filter($alias);
		$ret=array();

		$folder=$CONFIG['workspace']['path']."\\$login\\$alias\\api\\";
		$modelfolder=scandir($folder);
		for($i=2;$i<count($modelfolder);$i++){
			$model=$modelfolder[$i];
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
						$api=$this->setApi($model,$func,$desc);
						$ret[$model."_".$func]=$api;
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
	    	$model=$value["model"];
	    	$func=$value["func"];
	    	if(isset($ret["$model"."_"."$func"])){
	    		$ret["$model"."_"."$func"]["active"]=1;
	    	}
	    }
        $ret=setArrayNoNull($ret);

	    	//print_r($ret);
		return $ret;
	}
	
	public function setApi($model,$func,$desc=""){
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
			$model=$option["model"];
			$func=$option["func"];


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
    function apiCallLog($login,$alias,$model,$func,$output_data_length){
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $model=parameter_filter($model);
      $func=parameter_filter($func);
      $output_data_length=$output_data_length+0;


      $sql="insert into tb_app_calllog (login,alias,model,func,output_data_length,call_time,call_date) 
      values ('$login','$alias','$model','$func',$output_data_length,now(),current_date())";
        $query = $this->dbmgr->query($sql);
    }

	function generateDoc($appinfo,$apis){
	
		$ret="";
		$ret.="<h2>".$appinfo["name"]."数据交互接口使用说明书</h2>";
		$ret.="<br />";
		$ret.="<br />";
		foreach($apis as $model => $apifuncs){
			$ret.="<h3>".$model."模块</h2>";
			foreach($apifuncs as  $api){
				
				$ret.="<p><table>
				<tr>
					<th>接口路径</th>
					<td>/$model/".$api["func"]."</td>
				</tr>
				<tr>
					<th>接口说明</th>
					<td>".$api["description"]."</td>
				</tr>
				</table></p>";
			}
		}


		return $ret;
	}
 }

 
 
 $apiMgr=ApiMgr::getInstance();
 $apiMgr->dbmgr=$dbmgr;
 
 
 
 
?>