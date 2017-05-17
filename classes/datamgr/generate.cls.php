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
function ".$fmodel."Api()
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
    var $model=new ".$fmodel."Api();";
    
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


    
    
    public function generateTypeScript($login,$alias,$modellist){
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
        
        $modelfile=$apipath."\\$model.api.ts";
        $fmodel=ucfirst($model);
        $funcstr="";
		$funcdaostr="";
        $modelobj=$modellist[$model];


        foreach($funclist as $api){
        $description=$api["description"];
        $func=$api["func"];
        $url="$model/$func";
        
            if($api["type"]=="self"){
               $funcstr.="

//$description
public $func(data, showLoadingModal:boolean=true) {
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = ApiConfig.GetHeader(url, data);
        let options = new RequestOptions({ headers: headers });

        let body=ApiConfig.ParamUrlencoded(data);

        let loading: Loading=null;
        if(showLoadingModal){
          loading = ApiConfig.GetLoadingModal();
        }

        return this.http.post(url, body, options).toPromise()
            .then((res) => {
              if(ApiConfig.DataLoadedHandle('$url',data,res)){
                  if(showLoadingModal){
      					     ApiConfig.DimissLoadingModal();
                  }
      				
      					 return res.json();
      				}else{
                return Promise.reject(res);
              }
            })
            .catch(err => {
                
                if(showLoadingModal){
					         ApiConfig.DimissLoadingModal();
                }
                return ApiConfig.ErrorHandle('$url',data,err);
            });

        
    }
";
                

            }else{

                if($func=="list"){
                
               $funcstr.='
//'.$description.'
public list(search_condition_json, showLoadingModal:boolean=true) {
        var url = ApiConfig.getApiUrl()+"'.$url.'";
        var headers = ApiConfig.GetHeader(url, search_condition_json);
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(search_condition_json);


        let loading: Loading=null;
        if(showLoadingModal){
          loading = ApiConfig.GetLoadingModal();
        }

        return this.http.post(url, body, options).toPromise()
            .then((res) => {
                if(ApiConfig.DataLoadedHandle("'.$url.'",search_condition_json,res)){
                  if(showLoadingModal){
                     ApiConfig.DimissLoadingModal();
                  }
              
                 return res.json();
              }else{
                return Promise.reject(res);
              }
            })
            .catch(err => {
                if(showLoadingModal){
					           ApiConfig.DimissLoadingModal();
                }
                return ApiConfig.ErrorHandle("'.$url.'",search_condition_json,err);
            });

        
    }

';


               $funcdaostr.='
			   
	//'.$description.'
	public list(search_condition, showLoadingModel: boolean = true) {
        let api: '.$fmodel.'Api = new '.$fmodel.'Api(this.http);
        return api.list(search_condition, showLoadingModel).then(data => {
            this.batchUpdate(data);
            return data;
        }).catch(e => {
            return this.simpleQuery(search_condition);
        });
    }
	
	
	//'.$description.'
    public sync(search_condition = null, showLoadingModel: boolean = true) {
        let api: '.$fmodel.'Api = new '.$fmodel.'Api(this.http);
        return this.getLastestUpdatedTime().then((updatedate) => {
            return api.list({ "lastupdatecalltime": updatedate }, showLoadingModel).then(data => {
                alert(JSON.stringify(data));
                return this.batchUpdate(data).then(() => {
                    this.updateLatestUpdatedTime();
                    if (search_condition == null) {
                        return null;
                    }
                    return this.simpleQuery(search_condition);
                });
            }).catch(() => {
                if (search_condition == null) {
                    return null;
                }
                this.simpleQuery(search_condition);
            });
        }).catch(e => {
            if (search_condition == null) {
                return null;
            }
            return this.simpleQuery(search_condition);
        });
    }

';




                
                }elseif($func=="get"){
                
               $funcstr.="

//$description
public $func(id, showLoadingModal:boolean=true) {
        var url = ApiConfig.getApiUrl()+'$url';
        let json={ 'id' : id };
        var headers = ApiConfig.GetHeader(url, json);
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(json);


        let loading: Loading=null;
        if(showLoadingModal){
          loading = ApiConfig.GetLoadingModal();
        }

        return this.http.post(url, body, options).toPromise()
            .then((res) => {
              if(ApiConfig.DataLoadedHandle('$url',json,res)){
                  if(showLoadingModal){
                     ApiConfig.DimissLoadingModal();
                  }
              
                 return res.json();
              }else{
                return Promise.reject(res);
              }
            })
            .catch(err => {
                if(showLoadingModal){
                    ApiConfig.DimissLoadingModal();
                }
                return ApiConfig.ErrorHandle('$url',json,err);
            });

        
    }
";


$funcdaostr.='
			   
	//'.$description.'
    public get(id, showLoadingModel: boolean = true) {
        let api: '.$fmodel.'Api = new '.$fmodel.'Api(this.http);
        return api.get(id, showLoadingModel).then(data => {
            if (data != null) {
                return null;
            } 
            var lst = Array();
            lst.push(data);
            this.batchUpdate(lst);

            return data;

        }).catch(e => {
            return this.getOne(id);
        });
    }
';

                }elseif($func=="update"){
              
               $funcstr.="

//$description
public $func(update_json, showLoadingModal:boolean=true) {
        var url = ApiConfig.getApiUrl()+'$url';
        var headers = ApiConfig.GetHeader(url, update_json);
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(update_json);


        let loading: Loading=null;
        if(showLoadingModal){
          loading = ApiConfig.GetLoadingModal();
        }

        return this.http.post(url, body, options).toPromise()
            .then((res) => {

              if(ApiConfig.DataLoadedHandle('$url',update_json,res)){
                  if(showLoadingModal){
                     ApiConfig.DimissLoadingModal();
                  }
              
                 return res.json();
              }else{
                return Promise.reject(res);
              }
                                 
            })
            .catch(err => {
                                  if(showLoadingModal){
                                    ApiConfig.DimissLoadingModal();
                                  }
                return ApiConfig.ErrorHandle('$url',update_json,err);
            });

        
    }
";
                }elseif($func=="delete"){
                
               $funcstr.="

//$description
public $func(idlist, showLoadingModal:boolean=true) {
        var url = ApiConfig.getApiUrl()+'$url';
        let json={ 'idlist' : idlist };
        var headers = ApiConfig.GetHeader(url, json);
        let options = new RequestOptions({ headers: headers });
        let body=ApiConfig.ParamUrlencoded(json);


        let loading: Loading=null;
        if(showLoadingModal){
          loading = ApiConfig.GetLoadingModal();
        }

        return this.http.post(url, body, options).toPromise()
            .then((res) => {

              if(ApiConfig.DataLoadedHandle('$url',json,res)){
                  if(showLoadingModal){
                     ApiConfig.DimissLoadingModal();
                  }
              
                 return res.json();
              }else{
                return Promise.reject(res);
              }
                                  
            })
            .catch(err => {
                if(showLoadingModal){
                    ApiConfig.DimissLoadingModal();
                }
                return ApiConfig.ErrorHandle('$url',json,err);
            });

        
    }
";
                
                }
            }
            copy(ROOT."\\workspace_copy\\development\\typescript\\providers\\api.ts",$modelfile);
            file_put_contents($modelfile,str_replace('{{$modelname}}',$fmodel."Api",file_get_contents($modelfile))); 
            file_put_contents($modelfile,str_replace('{{funclist}}',$funcstr,file_get_contents($modelfile)));
			
			if($funcdaostr!=""){
				$funcdaostr='
				
		public tableName() {
			return "'.$model.'";
		}

		public tableColumns() {
			var columns = {};';
		foreach($modelobj["fields"]["field"] as $field){
		  if($field["type"]=="number"){
			$funcstr.='
			columns["'.$field["key"].'"] = "int";//'.$field["name"];
		  }elseif($field["type"]=="fkey"){
			$funcstr.='
			columns["'.$field["key"].'"] = "int";//'.$field["name"];
			$funcstr.='
			columns["'.$field["key"].'_name"] = "varchar";//'.$field["name"];
		  }elseif($field["type"]=="select"){
			$funcstr.='
			columns["'.$field["key"].'"] = "varchar";//'.$field["name"];
			$funcstr.='
			columns["'.$field["key"].'_name"] = "varchar";//'.$field["name"];
		  }elseif($field["type"]=="flist"&&$field["relatetable"]!=""){

		  }elseif($field["type"]=="grid"){

		  }else{
			$funcstr.='
			columns["'.$field["key"].'"] = "varchar";//'.$field["name"];
		  }
		}

			$funcstr.='
			return columns;
		}
				'.$funcdaostr;
				
			$modelfile=$apipath."\\$model.dao.ts";
            copy(ROOT."\\workspace_copy\\development\\typescript\\providers\\dao.ts",$modelfile);
            file_put_contents($modelfile,str_replace('{{fmodel}}',$fmodel,file_get_contents($modelfile))); 
            file_put_contents($modelfile,str_replace('{{funclist}}',$funcstr,file_get_contents($funcdaostr)));
				
			}
			
			
			

        }
      }

      mkdir($path."\\app");
      copy(ROOT."\\workspace_copy\\development\\typescript\\app\\api.config.ts",$path."\\app\\api.config.ts");
      $apiconfig=$path."\\app\\api.config.ts";

      file_put_contents($apiconfig,str_replace('{{$myapiaddress}}',$CONFIG['workspace']['domain']."/$login/$alias/api/",file_get_contents($apiconfig)));

      file_put_contents($apiconfig,str_replace('{{$myapiuploadaddress}}',$CONFIG['workspace']['domain']."/$login/$alias/upload/",file_get_contents($apiconfig)));

      file_put_contents($apiconfig,str_replace('{{$myapifileuploadaddress}}',$CONFIG['workspace']['domain']."/$login/$alias/fileupload",file_get_contents($apiconfig)));
	  
      file_put_contents($apiconfig,str_replace('{{$dbname}}',$login."_".$alias,file_get_contents($apiconfig)));

	  
      copy(ROOT."\\workspace_copy\\development\\typescript\\app\\app.util.ts",$path."\\app\\app.util.ts");
      $util=$path."\\app\\app.util.ts";
      file_put_contents($util,str_replace('{{aliaslogin}}',"/$login/$alias",file_get_contents($util)));

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
                            $l=substr($l,0,$lastkh).substr($l,$lastkh+1);
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
        if($field["type"]=="fkey"
          ||$field["type"]=="number"
          ||$field["type"]=="select"
          ||$field["type"]=="datetime"){

          $content.='$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param["'.$field["key"].'"]."\'";';

        }
        else{
          $content.='$sql_where.=" and r_main.'.$field["key"].' like \'%".$search_param["'.$field["key"].'"]."%\'";';
        }
    $content.='
    }';
    if($field["type"]=="fkey"){
    $content.='
    if(isset($search_param["'.$field["key"].'_name"]))
    {
        ';
        //$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param['.$field["key"].']."\'";
        if($field["type"]=="fkey"){

          $content.='$sql_where.=" and '.$field["ntbname"].'.'.$field["displayfield"].'=\'".$search_param["'.$field["key"].'_name"]."\'";';

        }
    $content.='
    }
  ';   
    }           
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
              $insertsql="insert into ".$modelobj["tablename"]." (id,created_user,created_date,updated_user,updated_date";
              $updatesql="update ".$modelobj["tablename"]." set updated_user=-1, updated_date=now() ";
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
              $insertsql=$insertsql.'$id,-1,now(),-1,now()';
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

  $sql="update '.$modelobj["tablename"].' set status=\'D\' and updated_date=now() where id in ($idlist)";
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


    public function generateCSharp($login,$alias,$modellist){
    Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $apilist=$this->getOutApiList($login,$alias);

      $urlhead=$CONFIG['workspace']['domain']."/$login/$alias/api/";

      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\";
      if(!file_exists($path)){
        mkdir($path,true);
      }
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\development\\csharp";
      if(!file_exists($path)){
        mkdir($path,true);
      }else{
        delDir($path);
      }

      $apipath=$path."\\AppLink.api";
      if(!file_exists($apipath)){
        mkdir($apipath,true);
      }

      
      $functionreplace="";
      $ItemGroupReplace="";

      foreach($apilist as $model=> $funclist){
        
        $modelfile=$apipath."\\$model.cs";
        $ItemGroupReplace.='<Compile Include="'.$model.'.cs" />
        ';
        $fmodel=ucfirst($model);
        $modelobj=$modellist[$model];
        $content="public static class $fmodel".'Mgr 
{
';
        
        foreach($funclist as $api){
        $description=$api["description"];
        $func=$api["func"];
        $url=$urlhead."$model/$func";
        $content.='
        /// <summary>
        /// '.$description.'
        /// </summary>
        ';
            
               $content.='
        /// <param name="api">Get from APIFactory.GetInstance()</param>
        /// <param name="requsetParam">list.add(new AppLink.core.Param("key","value")</param>
        /// <returns>Object[] or Object to change to Dictionary<string,string>[] or Dictionary<string,string></returns>
               ';
                $content.='        public static Object '.$func.'(APIInstance api, List<Param> requsetParam){
              return api.CallApi("'.$model.'/'.$func.'", requsetParam);
        }
';
        $content.='
        /// <param name="api">Get from APIFactory.GetInstance()</param>
        /// <param name="requestParam">list.add(new AppLink.core.Param("key","value")</param>
        /// <param name="requestParam">Async Call back to get data</param>
        ';
        $content.='        public static void '.$func.'(APIInstance api, List<Param> requsetParam,APIInstance.CallbackDelegate callback){
              api.CallApiAsync("'.$model.'/'.$func.'", requsetParam, callback);
        }
';


            {

                if($func=="list"){
                    
                    $content.='
public static DataTable list(DBInstance dbmgr,List<Param> searchParam,string orderby=""){

    StringBuilder sql_where=new StringBuilder();

';
                $search_cond="";
                if(trim($modelobj["searchcondition"])!=""){
                    $search_cond=" and ".$modelobj["searchcondition"];
                }
                $sql_key=" r_main.id ";
                $sql_table=" from  ".$modelobj["tablename"]." r_main ";
                foreach($modelobj["fields"]["field"] as $field){
                    $content.='
    if(Param.FindContainParamKey(searchParam,"'.$field["key"].'"))
    {
        ';
        //$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param['.$field["key"].']."\'";
        if($field["type"]=="fkey"
          ||$field["type"]=="number"
          ||$field["type"]=="select"
          ||$field["type"]=="datetime"){
          //
          $content.='sql_where.Append(" and r_main.'.$field["key"].'=@'.$field["key"].' ");';
        }
        else{
          
          $content.='sql_where.Append(" and r_main.'.$field["key"].' like @'.$field["key"].' ");';
        }
    $content.='
    }';
    if($field["type"]=="fkey"){
    $content.='
    if(Param.FindContainParamKey(searchParam,"'.$field["key"].'_name"))
    {
        ';
        //$sql_where.=" and r_main.'.$field["key"].'=\'".$search_param['.$field["key"].']."\'";
        if($field["type"]=="fkey"){

          $content.='sql_where.Append(" and '.$field["ntbname"].'.'.$field["displayfield"].' like @'.$field["key"].'_name ");';

        }
    $content.='
    }
  ';   
    }           
                  if($field["type"]=="datetime"){
                    $content.='
    if(Param.FindContainParamKey(searchParam,"'.$field["key"].'_from"))
    {
        sql_where.Append(" and r_main.'.$field["key"].'>=@'.$field["key"].'_from ");
    }

    if(Param.FindContainParamKey(searchParam,"'.$field["key"].'_to"))
    {
        sql_where.Append(" and r_main.'.$field["key"].'<=@'.$field["key"].'_to  ");
    }
  ';        

                  }



                  if($field["type"]=="flist"&&$field["relatetable"]!=""){
                    $table=$field["relatetable"];
                    
                    $sql_key=$sql_key." ,'' ".$field["key"];
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
    string sql="select '.$sql_key.' '.$sql_table.' where 1=1 "+sql_where.ToString()+" '.$search_cond.' "+orderby;
                
                return dbmgr.ExecuteDataTable(sql, searchParam);
';
                $content.='
}
';

                }elseif($func=="get"){

$content.='
public static Dictionary<string,object> '.$func.'(DBInstance dbmgr,int id){

  ';

  $sql_key=" r_main.id ";
                $sql_table=" from  ".$modelobj["tablename"]." r_main ";
                foreach($modelobj["fields"]["field"] as $field){
                   


                  if($field["type"]=="flist"&&$field["relatetable"]!=""){
                    $table=$field["relatetable"];
                    
                    $sql_key=$sql_key." ,'' ".$field["key"];
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


    Dictionary<string,object> retDict=new Dictionary<string,object>();


    string sql="select '.$sql_key.' '.$sql_table.' where r_main.id="+id.ToString();
                DataTable dt=dbmgr.ExecuteDataTable(sql, null);
                if (dt.Rows.Count == 0)
                {
                    return retDict;
                }

                DataRow dr = dt.Rows[0];
                foreach (DataColumn item in dt.Columns)
                {
                    retDict.Add(item.ColumnName, dr[item.ColumnName]);
                }

                ';
                foreach($modelobj["fields"]["field"] as $field){
                    if($field["type"]=="flist"){

                      $r_tablename=$field["tablename"];
                      $r_relatetable=$field["relatetable"];
                      $r_ntbname=$field["ntbname"];
                      $r_condition=empty($field["condition"])?"":" and ".$field["condition"];

                      if($field["relatetable"]!=""){
                        $content.='
                          string flistsql="select '.$r_ntbname.'.* from '.$r_relatetable.' 
                          inner join '.$r_tablename.' '.$r_ntbname.' on '.$r_relatetable.'.fid='.$r_ntbname.'.id
                          where '.$r_relatetable.'.pid="+id.ToString()+" '.$r_condition.' ";
                          DataTable flistdt= dbmgr.ExecuteDataTable(flistsql, null);

                          retDict.Add("'.$field["key"].'", flistdt);
                        ';
                      }else{

                        $content.='
                          if(!string.IsNullOrEmpty(dr["'.$field["key"].'"].ToString())){
                            string idlist="0".$result["'.$field["key"].'"];
                            string flistsql="select '.$r_ntbname.'.* from  '.$r_tablename.' '.$r_ntbname.'
                            where id in ( "+idlist+" )  '.$r_condition.' ";
                            DataTable flistdt= dbmgr.ExecuteDataTable(flistsql, null);

                            retDict.Add("'.$field["key"].'", flistdt);
                          }
                        ';
                      }
                    }
                }

                $content.='


                return retDict;
';
                $content.='
}
';
                
                }elseif($func=="update"){


$content.='
public static int '.$func.'(DBInstance dbmgr,List<Param> request,int id=0){
  //id=0为插入新字段
  ';
              $insertsql="insert into ".$modelobj["tablename"]." (id,created_user,created_date,updated_user,updated_date";
              $updatesql="update ".$modelobj["tablename"]." set updated_user=-2, updated_date=now() ";
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
              $insertsql=$insertsql.'"+id.ToString()+",-2,now(),-2,now()';
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

                  $insertsql=$insertsql.',@'.$value["key"].' ';
                  $updatesql=$updatesql.',`'.$value["key"].'`=@'.$value["key"].' ';

                if($value["type"]=="password"){
                  $content.=' Param.GetParam("'.$value["key"].'").Value=Utils.MD5Encrypt(Param.GetParam("'.$value["key"].'").Value.ToString()); ';
                }



                if($value["type"]=="check"&&$value["unique"]=="1"&&parameter_filter($request[$value["key"]])=="Y"){
                  $otherupdatesql.=' dbmgr.ExecuteNonQuery(tx,"update "'.$modelobj["tablename"].'" set '.$value["key"].'=\'N\' where ".$request["'.$value["key"].'"].=\'Y\'",null);';
                }
                if($value["type"]=="flist"&&$value["relatetable"]!=""){
                  $otherupdatesql.='
                  string[] fidlist=Param.GetParam(request,"'.$value["key"].'").Value.ToString().Split(new string[] { "," }, StringSplitOptions.RemoveEmptyEntries);
                  foreach (string fid as fidlist) {
                    dbmgr.ExecuteNonQuery(tx,"insert into '.$value["relatetable"].' values("+id.ToString()+","+fid.ToString()+")",null);
                  }
                  ';
                  
                }
              }

            $content.=' 
              using (DbConnection conn = dbmgr.GetDbConnection())
            {
                conn.Open();
                using (DbTransaction tx = conn.BeginTransaction())
                {

                string sql="";

                if(id==0){
                  id=dbmgr.getNewId("'.$modelobj["tablename"].'");
                  sql="'.$insertsql.' )";
                }else{
                  sql="'.$updatesql.' where id="+id.ToString();
                }

                dbmgr.ExecuteNonQuery(tx,sql,request);

                '.$otherupdatesql.'
                

                tx.Commit();
                return id;

              }
            }
            ';


                $content.='
}
';
              
                }elseif($func=="delete"){

$content.='
public static void '.$func.'(DBInstance dbmgr, List<int> idlist){
  string stridlist = string.Join(",", idlist);
using (DbConnection conn = dbmgr.GetDbConnection())
            {
                conn.Open();
                using (DbTransaction tx = conn.BeginTransaction()){

  string sql="update '.$modelobj["tablename"].' set status=\'D\' where id in ("+stridlist+")";
  dbmgr.ExecuteNonQuery(tx,sql,null);

   tx.Commit();
}
}
  ';      
                $content.='
}
';

                
                }
                
            }
            //copy(ROOT."\\workspace_copy\\development\\typescript\\providers\\test.ts",$modelfile);
           // file_put_contents($modelfile,str_replace('{{$modelname}}',$fmodel,file_get_contents($modelfile))); 
            //file_put_contents($modelfile,str_replace('{{funclist}}',$funcstr,file_get_contents($modelfile))); 

        }

        $content.="
}
"; 
        //echo $content;
        $content="using AppLink.core;
using System;
using System.Collections.Generic;
using System.Data;
using System.Data.Common;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace AppLink.api
{
  $content
}
";
           file_put_contents($modelfile, $content);
      }



      recurse_copy(ROOT."\\workspace_copy\\development\\csharp\\",$path);

      $filename=$apipath."\\AppLink.api.csproj";
      file_put_contents($filename,str_replace('<!--repacefilehere-->',$ItemGroupReplace,file_get_contents($filename)));


      $filename=$path."\\AppLink.example\\App.config";
      file_put_contents($filename,str_replace('{{applink.mysql.hosts}}',"mysql.app-link.org",file_get_contents($filename)));
      file_put_contents($filename,str_replace('{{applink.mysql.userid}}',$login,file_get_contents($filename)));
      file_put_contents($filename,str_replace('{{applink.mysql.password}}',md5($login."_49339"),file_get_contents($filename)));
      file_put_contents($filename,str_replace('{{applink.mysql.database}}',$login."_".$alias,file_get_contents($filename)));
      file_put_contents($filename,str_replace('{{applink.api}}',"http://cmsdev.app-link.org/$login/$alias/api/",file_get_contents($filename)));

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