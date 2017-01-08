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

    public function generateWeb($login,$alias){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\web";
      if(!file_exists($path)){
        mkdir($path,true);
      }else{
        delDir($path);
      }

      $apipath=$path."\\api";
      if(!file_exists($apipath)){
        mkdir($apipath,true);
      }

      
      $jsreplace="";
      $trreplace="";
      $functionreplace="";


      foreach($apilist as $model=> $funclist){
        
        $modelfile=$apipath."\\$model.js";
        $modelfile = fopen($modelfile, "w");


        $jsreplace.="<script src=\"api/$model.js\"></script>";

        $fmodel=ucfirst($model);
        $jsstr="
function $fmodel()
{
";
        foreach($funclist as $api){
        $description=$api["description"];
        $jsstr.="   //$description";
        $func=$api["func"];

        $trreplace.="
		<tr id=\"tr_".$model."_$func\">
			<td>$model/$func</td>
			<td><textarea class=\"input\"></textarea></td>
			<td><button onclick=\"try_".$model."_$func();\">测试</button></td>
			<td><textarea class=\"output\"></textarea></td>
		</tr>";



        $url=$urlhead."$model/$func";
        $repinput=true;

            if($api["type"]=="self"){
                $jsstr.="
    this.$func = function(request_json,callback){
        $.post('$url',request_json,callback);
    };

";
            }else{

                if($func=="list"){
                
                $jsstr.="
    this.$func = function(search_json,callback){
        $.post('$url',search_json,callback);
    };

";
                }elseif($func=="get"){
                $repinput=false;
                $jsstr.="
    this.$func = function(id,callback){
        var json={id:id};
        $.post('$url',json,callback);
    };

";
                }elseif($func=="update"){
                
                $jsstr.="
    this.$func = function(field_json,callback){
        field_json.primary_id=field_json.id;
        $.post('$url',field_json,callback);
    };

";
                }elseif($func=="delete"){
                
                $repinput=false;
                $jsstr.="
    this.$func = function(id_array,callback){
        var json={idlist:id_array};
        $.post('$url',json,callback);
    };

";
                }
            }
            $functionreplace.="function try_".$model."_$func(){
		var input=$(\"#tr_".$model."_$func .input\").val();
		var $model=new $fmodel();";
		
        if($repinput){
        $functionreplace.="try{
			if(input!=\"\")
			input=JSON.parse(input);
		}catch(e){
			$(\"#tr_".$model."_$func .output\").val(\"输入json错误\"+e.message );
			return;
		}";
        }
		
		$functionreplace.="
        try{
			$model.$func(input,function(data){
				$(\"#tr_".$model."_$func .output\").val(data);
			});
		}catch(e){
			$(\"#tr_".$model."_$func .output\").val(e.message );
			return;
		}
	}";


        }
        $jsstr.="
}";


        fwrite($modelfile, $jsstr);
    
        fclose($modelfile);
      }
      recurse_copy(ROOT."\\workspace_copy\\development\\web\\",$path);

      
      $apitester=$path."\\apitester.html";
      file_put_contents($apitester,str_replace('{{jsreplace}}',$jsreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{trreplace}}',$trreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{functionreplace}}',$functionreplace,file_get_contents($apitester))); 


      exit;
      return $path;
    }

    
    public function generateMobile($login,$alias){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\mobile";
      if(!file_exists($path)){
        mkdir($path,true);
      }else{
        delDir($path);
      }

      $apipath=$path."\\api";
      if(!file_exists($apipath)){
        mkdir($apipath,true);
      }

      
      $jsreplace="";
      $trreplace="";
      $functionreplace="";


      foreach($apilist as $model=> $funclist){
        
        $modelfile=$apipath."\\$model.js";
        $modelfile = fopen($modelfile, "w");


        $jsreplace.="<script src=\"api/$model.js\"></script>";

        $fmodel=ucfirst($model);
        $jsstr="
function $fmodel()
{
";
        foreach($funclist as $api){
        $description=$api["description"];
        $jsstr.="   //$description";
        $func=$api["func"];

        $trreplace.="
		<tr id=\"tr_".$model."_$func\">
			<td>$model/$func</td>
			<td><textarea class=\"input\"></textarea></td>
			<td><button onclick=\"try_".$model."_$func();\">测试</button></td>
			<td><textarea class=\"output\"></textarea></td>
		</tr>";



        $url=$urlhead."$model/$func";
        $repinput=true;

            if($api["type"]=="self"){
                $jsstr.="
    this.$func = function(request_json,callback){
        $.post('$url',request_json,callback);
    };

";
            }else{

                if($func=="list"){
                
                $jsstr.="
    this.$func = function(search_json,callback){
        $.post('$url',search_json,callback);
    };

";
                }elseif($func=="get"){
                $repinput=false;
                $jsstr.="
    this.$func = function(id,callback){
        var json={id:id};
        $.post('$url',json,callback);
    };

";
                }elseif($func=="update"){
                
                $jsstr.="
    this.$func = function(field_json,callback){
        field_json.primary_id=field_json.id;
        $.post('$url',field_json,callback);
    };

";
                }elseif($func=="delete"){
                
                $repinput=false;
                $jsstr.="
    this.$func = function(id_array,callback){
        var json={idlist:id_array.join(',')};
        $.post('$url',json,callback);
    };

";
                }
            }
            $functionreplace.="function try_".$model."_$func(){
		var input=$(\"#tr_".$model."_$func .input\").val();
		var $model=new $fmodel();";
		
        if($repinput){
        $functionreplace.="try{
			if(input!=\"\")
			input=JSON.parse(input);
		}catch(e){
			$(\"#tr_".$model."_$func .output\").val(\"输入json错误\"+e.message );
			return;
		}";
        }
		
		$functionreplace.="
        try{
			$model.$func(input,function(data){
				$(\"#tr_".$model."_$func .output\").val(data);
			});
		}catch(e){
			$(\"#tr_".$model."_$func .output\").val(e.message );
			return;
		}
	}";


        }
        $jsstr.="
}";


        fwrite($modelfile, $jsstr);
    
        fclose($modelfile);
      }
      recurse_copy(ROOT."\\workspace_copy\\development\\mobile\\",$path);

      
      $apitester=$path."\\apitester.html";
      file_put_contents($apitester,str_replace('{{jsreplace}}',$jsreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{trreplace}}',$trreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{functionreplace}}',$functionreplace,file_get_contents($apitester))); 


      exit;
      return $path;
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

	    foreach ($aplconfig["apis"]["api"] as $key => $value) {
	    	$type=$value["type"];
	    	$model=$value["model"];
	    	$func=$value["func"];
	    	$active=$value["active"];
	    	$output=$value["output"];
	    	$input=$value["input"];
            if($active=="1"){
                $ret[$model][]=$value;
            }
	    }
        $ret=setArrayNoNull($ret);
        $ret=bubbleSort($ret,"type");
        $ret=bubbleSort($ret,"func");
        $ret=bubbleSort($ret,"model");
		return $ret;
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