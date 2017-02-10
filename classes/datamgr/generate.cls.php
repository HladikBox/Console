<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class GenerateMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new GenerateMgr();
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

      $urlhead="dataapi_link";

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

      
      $jsreplace="<script src=\"api/api.config.js\"></script>\r\n";
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



        $url="/$model/$func";
        $repinput=true;

            if($api["type"]=="self"){
                $jsstr.="
    this.$func = function(request_json,callback){
        $.post($urlhead+'$url',request_json,callback);
    };

";
            }else{

                if($func=="list"){
                
                $jsstr.="
    this.$func = function(search_json,callback){
        $.post($urlhead+'$url',search_json,callback);
    };

";
                }elseif($func=="get"){
                $repinput=false;
                $jsstr.="
    this.$func = function(id,callback){
        var json={id:id};
        $.post($urlhead+'$url',json,callback);
    };

";
                }elseif($func=="update"){
                
                $jsstr.="
    this.$func = function(field_json,callback){
        field_json.primary_id=field_json.id;
        $.post($urlhead+'$url',field_json,callback);
    };

";
                }elseif($func=="delete"){
                
                $repinput=false;
                $jsstr.="
    this.$func = function(id_array,callback){
        var json={idlist:id_array};
        $.post($urlhead+'$url',json,callback);
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

      $apiconfig=$path."\\api\\api.config.js";
      file_put_contents($apiconfig,str_replace('{{dataapi_link}}',$CONFIG['workspace']['domain']."/$login/$alias/api",file_get_contents($apiconfig)));
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
        $url="$model/$func";
        
            if($api["type"]=="self"){
               $funcstr.="

//$description
public $func(data) {
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        });;
        let options = new RequestOptions({ headers: headers });

        let body=ApiConfig.ParamUrlencoded(data);
        return this.http.post(url, body, options).toPromise()
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
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        });;
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(search_condition_json);
        return this.http.post(url, body, options).toPromise()
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
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        });;
        let options = new RequestOptions({ headers: headers });
        let json={ 'id' : id };
        let body=ApiConfig.ParamUrlencoded(json);
        return this.http.post(url, body, options).toPromise()
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
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        });;
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(update_json);
        return this.http.post(url, body, options).toPromise()
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
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = new Headers({
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json'
        });;
        let options = new RequestOptions({ headers: headers });
        let json={ 'idlist' : idlist };
        let body=ApiConfig.ParamUrlencoded(json);
        return this.http.post(url, body, options).toPromise()
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

      mkdir($path."\\app");
      copy(ROOT."\\workspace_copy\\development\\typescript\\app\\api.config.ts",$path."\\app\\api.config.ts");
      $apiconfig=$path."\\app\\api.config.ts";

      file_put_contents($apiconfig,str_replace('{{$myapiaddress}}',$CONFIG['workspace']['domain']."/$login/$alias/api/",file_get_contents($apiconfig)));
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
public function _list($search_param,$orderby){

    $sql_where="";

';
                $search_cond="";
                if(trim($modelobj["searchcondition"])!=""){
                    $search_cond=" and ".$modelobj["searchcondition"];
                }
                $sql_key=" r_main.id ";
                $sql_table=" from  ".$modelobj["tablename"]." r_main ";
                foreach($modelobj["fields"]["field"] as $field){
                    $content.='
    if(isset($search_param["'.$field["key"].'"]))
    {
        ';
        //$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param['.$field["key"].']."\'";
        if($field["type"]=="fkey"){

          $content.='$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param["'.$field["key"].'"]."\'";';

        }elseif($field["type"]=="datetime"){
          //
        }
        else{
          $content.='$sql_where.=" and r_main.'.$field["key"].' like \'%".$search_param["'.$field["key"].'"]."%\'";';
        }
    $content.='
    }
  ';              
                  if($field["type"]=="datetime"){
                    $content.='
    if(isset($search_param["'.$field["key"].'_from"]))
    {
        $sql_where.=" and r_main.'.$field["key"].'>=\'".$search_param["'.$field["key"].'_from"]."\'";
    }

    if(isset($search_param["'.$field["key"].'_to"]))
    {
        $sql_where.=" and r_main.'.$field["key"].'<=\'".$search_param["'.$field["key"].'_to"]."\'";
    }
  ';        

                  }



                  if($field["type"]=="flist"&&$field["relatetable"]!=""){
                    $table=$field["relatetable"];
                    
                    $sql_key=$sql_key." ,'' ".$field["key"];
                  }else if($field["type"]=="select"){

                    $sql_key=$sql_key." ,case   r_main.".$field["key"]." ";
                    foreach ($field["options"]["option"] as $option){
                      $sql_key=$sql_key." when '".$option["value"]."' then '".$option["name"]."'";
                    }
                    $sql_key=$sql_key." else 'unknow' ";
                    $sql_key=$sql_key." end as ".$field["key"];

                  }else if($field["type"]=="check"){

                    $sql_key=$sql_key." ,case   r_main.".$field["key"]." when 'Y' then '".$field["yvalue"]."' else '".$field["nvalue"]."' ";
                    $sql_key=$sql_key." end as ".$field["key"];

                  }else if($field["type"]=="fkey"){
                  
                    $sql_key=$sql_key." ,".$field["ntbname"].".".$field["displayfield"]." ".$field["key"]."_name,r_main.".$field["key"];

                  }else{

                    $sql_key=$sql_key." ,r_main.".$field["key"];

                  }


                   if($field["type"]=="fkey"){
                        $sql_table=$sql_table." left join ".$field["tablename"]." ".$field["ntbname"]." on r_main.".$field["key"]."=".$field["ntbname"].".id ";
                   }

                }

                $content.='
    $sql="select '.$sql_key.' '.$sql_table.' where 1=1 $sql_where '.$search_cond.'
    $orderby";
                $query = $this->dbmgr->query($sql);
                $result = $this->dbmgr->fetch_array_all($query);

                return $result;
';

                }elseif($func=="get"){

$content.='
public function '.$func.'($id){

  ';



  $sql_key=" r_main.id ";
                $sql_table=" from  ".$modelobj["tablename"]." r_main ";
                foreach($modelobj["fields"]["field"] as $field){
                   


                  if($field["type"]=="flist"&&$field["relatetable"]!=""){
                    $table=$field["relatetable"];
                    
                    $sql_key=$sql_key." ,'' ".$field["key"];
                  }else if($field["type"]=="select"){

                    $sql_key=$sql_key." ,case   r_main.".$field["key"]." ";
                    foreach ($field["options"]["option"] as $option){
                      $sql_key=$sql_key." when '".$option["value"]."' then '".$option["name"]."'";
                    }
                    $sql_key=$sql_key." else 'unknow' ";
                    $sql_key=$sql_key." end as ".$field["key"];

                  }else if($field["type"]=="check"){

                    $sql_key=$sql_key." ,case   r_main.".$field["key"]." when 'Y' then '".$field["yvalue"]."' else '".$field["nvalue"]."' ";
                    $sql_key=$sql_key." end as ".$field["key"];

                  }else if($field["type"]=="fkey"){
                  
                    $sql_key=$sql_key." ,".$field["ntbname"].".".$field["displayfield"]." ".$field["key"]."_name,r_main.".$field["key"];

                  }else{

                    $sql_key=$sql_key." ,r_main.".$field["key"];

                  }


                   if($field["type"]=="fkey"){
                        $sql_table=$sql_table." left join ".$field["tablename"]." ".$field["ntbname"]." on r_main.".$field["key"]."=".$field["ntbname"].".id ";
                   }

                }

                $content.='
    $sql="select '.$sql_key.' '.$sql_table.' where r_main.id=$id ";
                $query = $this->dbmgr->query($sql);
                $result = $this->dbmgr->fetch_array($query);

                ';
                foreach($modelobj["fields"]["field"] as $field){
                    if($field["type"]=="flist"){

                      $r_tablename=$field["tablename"];
                      $r_relatetable=$field["relatetable"];
                      $r_ntbname=$field["ntbname"];
                      $r_condition=empty($field["condition"])?"":" and ".$field["condition"];

                      if($field["relatetable"]!=""){
                        $content.='
                          $sql="select '.$r_ntbname.'.* from '.$r_relatetable.' 
                          inner join '.$r_tablename.' '.$r_ntbname.' on '.$r_relatetable.'.fid='.$r_ntbname.'.id
                          where '.$r_relatetable.'.pid=$id '.$r_condition.' ";
                          $query = $this->dbmgr->query($sql);
                          $rs = $this->dbmgr->fetch_array_all($query);

                          $result["'.$field["key"].'"]=$rs;

                        ';
                      }else{

                        $content.='
                          if(!empty($result["'.$field["key"].'"])){
                            $idlist="0".$result["'.$field["key"].'"];
                            $sql="select '.$r_ntbname.'.* from  '.$r_tablename.' '.$r_ntbname.'
                            where id in ( $idlist )  '.$r_condition.' ";
                            $query = $this->dbmgr->query($sql);
                            $rs = $this->dbmgr->fetch_array_all($query);

                            $result["'.$field["key"].'"]=$rs;
                          }
                        ';
                      }
                    }
                }

                $content.='


                return $result;
';
                
                }elseif($func=="update"){


$content.='
public function '.$func.'($request,$id=0){
  //id=0为插入新字段
  ';
              $insertsql="insert into ".$modelobj["tablename"]." (id";
              $updatesql="update ".$modelobj["tablename"]." set updated_date=now() ";
              $otherupdatesql="";
              foreach($modelobj["fields"]["field"] as $value){
                if($value["ismutillang"]=="1"){
                  $haveMutilLang=true;
                  continue;
                }
                if($value["type"]=="grid"){
                  continue;
                }
                if($value["type"]=="flist"&&$value["relatetable"]!=""){
                  continue;
                }
                if($value["nosave"]=="1"){
                  continue;
                }
                $insertsql=$insertsql.",`".$value["key"]."`";
              }
              $insertsql=$insertsql." ) values (";
              $insertsql=$insertsql.'$id';
              foreach($modelobj["fields"]["field"] as $value){
                
                
                if($value["type"]=="grid"
                ||$value["ismutillang"]){
                  continue;
                }
                if($value["type"]=="flist"&&$value["relatetable"]!=""){
                  continue;
                }
                if($value["nosave"]=="1"){
                  continue;
                }

                if($value["type"]=="password"){
                  $insertsql=$insertsql.',\'".md5($request["'.$value["key"].'"])."\'';
                  $updatesql=$updatesql.',`'.$value["key"].'`=\'".md5($request["'.$value["key"].'"])."\'';
                }else{
                  $insertsql=$insertsql.',\'".parameter_filter($request["'.$value["key"].'"])."\'';
                  $updatesql=$updatesql.',`'.$value["key"].'`=\'".parameter_filter($request["'.$value["key"].'"])."\'';
                }
                if($value["type"]=="check"&&$value["unique"]=="1"&&parameter_filter($request[$value["key"]])=="Y"){
                  $otherupdatesql.=' $this->dbmgr->query("update "'.$modelobj["tablename"].'" set '.$value["key"].'=\'N\' where ".$request["'.$value["key"].'"].=\'Y\'");';
                }
                if($value["type"]=="flist"&&$value["relatetable"]!=""){
                  $otherupdatesql.='
                  $fidlist=explode(\',\', $request["'.$value["key"].'"]);
                  foreach ($fidlist as  $fid) {
                    $this->dbmgr->query("insert into '.$value["relatetable"].' values($id,$fid)");
                  }
                  ';
                  
                }
              }

            $content.=' 
              $id=$id+0;
              $this->dbmgr->begin_trans();

              if($id==0){
                $id=$this->dbmgr->getNewId("'.$modelobj["tablename"].'");
                $sql="'.$insertsql.' )";
              }else{
                $sql="'.$updatesql.' where id=$id";
              }

              '.$otherupdatesql.'
              $this->dbmgr->query($sql);

              $this->dbmgr->commit_trans();
              return $id;
            ';


              
                }elseif($func=="delete"){

$content.='
public function '.$func.'($idlist){
  $idlist=explode(",",$idlist);
    for($i=0;$i<count($idlist);$i++){
        $idlist[$i]=$idlist[$i]+0;
    }

    $idlist=join(",",$idlist);
    $dbMgr->begin_trans();

  $sql="update '.$modelobj["tablename"].' set status=\'D\' where id in ($idlist)";
  $query = $this->dbmgr->query($sql);

    $dbMgr->commit_trans();
    return true;
  ';      

                
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
        //echo $content;
        $content="<?php
$content
?>";
           file_put_contents($modelfile, $content);
      }

      recurse_copy(ROOT."\\workspace_copy\\development\\php\\",$path);





      //exit;
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
 }
 
 $generateMgr=GenerateMgr::getInstance();
 $generateMgr->dbmgr=$dbmgr;
 
 
 
 
?>