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

    public function generateAjax($login,$alias){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\ajax";
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
      recurse_copy(ROOT."\\workspace_copy\\development\\ajax\\",$path);

      
      $apitester=$path."\\apitester.html";
      file_put_contents($apitester,str_replace('{{jsreplace}}',$jsreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{trreplace}}',$trreplace,file_get_contents($apitester))); 
      file_put_contents($apitester,str_replace('{{functionreplace}}',$functionreplace,file_get_contents($apitester))); 


      return $path;
    }


    
    
    public function generateTypeScript($login,$alias){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\typescript";
      if(!file_exists($path)){
        mkdir($path,true);
      }else{
        delDir($path);
      }

      $apipath=$path."\\providers";
      if(!file_exists($apipath)){
        mkdir($apipath,true);
      }

      
      $functionreplace="";


      foreach($apilist as $model=> $funclist){
        
        $modelfile=$apipath."\\$model.ts";
        $fmodel=ucfirst($model);
        $funcstr="";
        
        foreach($funclist as $api){
        $description=$api["description"];
        $func=$api["func"];
        $url=$urlhead."$model/$func";
        
            if($api["type"]=="self"){
               $funcstr.="

//$description
public $func(data) {
        var url = '$url';
        var headers = new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        return this.http.post(url, data, { headers: headers }).toPromise()
            .then(res => res.json())
            .catch(err => {
                this.handleError(err);
            });

        
    }
";
                

            }else{

                if($func=="list"){
                
               $funcstr.="

//$description
public $func(search_condition_json) {
        var url = '$url';
        var headers = new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        return this.http.post(url, search_condition_json, { headers: headers }).toPromise()
            .then(res => res.json())
            .catch(err => {
                this.handleError(err);
            });

        
    }
";
                
                }elseif($func=="get"){
                
               $funcstr.="

//$description
public $func(id) {
        var url = '$url';
        var headers = new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        let json={ 'id' : id };
        return this.http.post(url, json, { headers: headers }).toPromise()
            .then(res => res.json())
            .catch(err => {
                this.handleError(err);
            });

        
    }
";
                }elseif($func=="update"){
              
               $funcstr.="

//$description
public $func(update_json) {
        var url = '$url';
        var headers = new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        return this.http.post(url, update_json, { headers: headers }).toPromise()
            .then(res => res.json())
            .catch(err => {
                this.handleError(err);
            });

        
    }
";
                }elseif($func=="delete"){
                
               $funcstr.="

//$description
public $func(idlist) {
        var url = '$url';
        var headers = new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        let json={ 'idlist' : idlist };
        return this.http.post(url, json, { headers: headers }).toPromise()
            .then(res => res.json())
            .catch(err => {
                this.handleError(err);
            });

        
    }
";
                
                }
            }
            copy(ROOT."\\workspace_copy\\development\\typescript\\providers\\test.ts",$modelfile);
            file_put_contents($modelfile,str_replace('{{$modelname}}',$fmodel,file_get_contents($modelfile))); 
            file_put_contents($modelfile,str_replace('{{funclist}}',$funcstr,file_get_contents($modelfile))); 

        }
      }
      
      return $path;
    }

    
    
    public function generatePHP($login,$alias,$modellist){
		Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\php";
      if(!file_exists($path)){
        mkdir($path,true);
      }else{
        delDir($path);
      }

      $apipath=$path."\\model";
      if(!file_exists($apipath)){
        mkdir($apipath,true);
      }

      
      $functionreplace="";


      foreach($apilist as $model=> $funclist){
        
        $modelfile=$apipath."\\$model.php";
        $fmodel=ucfirst($model);
        $modelobj=$modellist[$model];
        $content="class $fmodel".'Mgr 
{
    public $dbmgr = null;
    public function __construct($dbmgr) {
	    $this->dbmgr=$dbmgr;	
    }
';
        
        foreach($funclist as $api){
        $description=$api["description"];
        $func=$api["func"];
        $url=$urlhead."$model/$func";
        $content.="//$description";
            if($api["type"]=="self"){
               
                $content.='
public function '.$func.'($param){';

                $apifuncfile=$CONFIG['workspace']['path']."\\$login\\$alias\\api\\$model\\$func.php";
                $lines = @file($apifuncfile);
                $start=false;
                foreach($lines as $l){
                    if($start){
                        $l=str_replace("<?php","",$l);
                        $l=str_replace("?>","",$l);
                        $l=str_replace('$dbmgr','$this->dbmgr',$l);
                        if(strstr($l,"outputJson(")){
                            $l=str_replace("outputJson(","return ",$l);
                            $lastkh=strrpos($l, ')', -1);
                            $l=substr($l,0,$lastkh-1).substr($l,$lastkh);
                        }
                        $content.=" ".$l;
                    }
                    if(substr(trim($l),0,13)=="////starthere"){
                        $start=true;
                    }
                }


                $content.='
}
';

            }else{

                if($func=="list"){
                    
                    $content.='
public function '.$func.'($search_param){


    $sql_where="";

';
                $search_cond="";
                if(trim($modelobj["searchcondition"])!=""){
                    $search_cond=" and ".$modelobj["searchcondition"];
                }
                foreach($modelobj["fields"]["field"] as $field){
                    $content.='
    if(isset($search_param["'.$field["key"].'"]))
    {
        $sql_where.=" and r_main.'.$field["key"].'=\'".$search_param['.$field["key"].']."\'";
    }
';
                }

                $content.='
    $sql="select * from '.$modelobj["tablename"].' r_main where 1=1 $sql_where '.$search_cond.';
';

                }elseif($func=="get"){


                
                }elseif($func=="update"){


              
                }elseif($func=="delete"){

                
                }
                
                $content.='
}
';
            }
            //copy(ROOT."\\workspace_copy\\development\\typescript\\providers\\test.ts",$modelfile);
           // file_put_contents($modelfile,str_replace('{{$modelname}}',$fmodel,file_get_contents($modelfile))); 
            //file_put_contents($modelfile,str_replace('{{funclist}}',$funcstr,file_get_contents($modelfile))); 

        }

        $content.="
}
"; 
        echo $content;
        $content="<?php
$content
?>";
           file_put_contents($modelfile, $content);
      }
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

                
				$api=$this->setApi("model",$model["modelname"],"get","获取<b style='color:blue'>".$model["name"]."详情</b>");
				$ret["model_".$model["modelname"]."_"."get"]=$api;

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
                if(!file_exists($apipath)){
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
 }
 
 $apiMgr=ApiMgr::getInstance();
 $apiMgr->dbmgr=$dbmgr;
 
 
 
 
?>