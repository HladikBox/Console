<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class ModelMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new ModelMgr();
	}


  public $keytypename;
  public $keydbtype;

	private function __construct() {
    $this->keytypename=array();
    $this->keytypename["text"]="文本";
    $this->keytypename["password"]="密码";
    $this->keytypename["check"]="选中框";
    $this->keytypename["longtext"]="长文本";
    $this->keytypename["select"]="下拉";
    $this->keytypename["html"]="HTML编辑";
    $this->keytypename["number"]="数值";
    $this->keytypename["upload"]="文件上传";
    $this->keytypename["grid"]="下级数据";
    $this->keytypename["datetime"]="日期时间";
    $this->keytypename["fkey"]="表关联下拉";
    $this->keytypename["flist"]="表关联多选";
    $this->keytypename["url"]="网络链接";
    $this->keytypename["color"]="颜色";


    $this->keydbtype=array();
    $this->keydbtype["text"]="varchar(255)";
    $this->keydbtype["password"]="varchar(255)";
    $this->keydbtype["check"]="char(1)";
    $this->keydbtype["longtext"]="varchar(2000)";
    $this->keydbtype["select"]="varchar(15)";
    $this->keydbtype["html"]="text";
    $this->keydbtype["number"]="int";
    $this->keydbtype["upload"]="varchar(100)";
    $this->keydbtype["datetime"]="datetime";
    $this->keydbtype["fkey"]="int";
    $this->keydbtype["grid"]="varchar(20)";
    $this->keydbtype["flist"]="varchar(4000)";
    $this->keydbtype["url"]="varchar(500)";
    $this->keydbtype["color"]="varchar(20)";
	}
	
	public  function __destruct ()
	{
		
	}
  private function xmlToArray( $xml )
  {
     return json_decode(json_encode((array) simplexml_load_string($xml)), true);
  }

  public function applyCommModel($login,$alias,$models){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    foreach ($models as  $value) {
      $filename=$value;
      $path=ROOT."/workspace_recommend/model/$filename.xml";
      copy($path,$folder.$filename.".xml");
      $path=ROOT."/workspace_recommend/modelmgr/$filename.model.php";
      copy($path,$folder.$filename.".model.php");
      $path=ROOT."/workspace_recommend/js/$filename.js";
      copy($path,$folder.$filename.".js");
    }
    return outResult(0,"","");
  }


  private function loadXmlFile($name){
    
    $path=ROOT."/model/$name.xml";
    if(!file_exists($path)){
        $path=USER_ROOT."/model/$name.xml";
        if(!file_exists($path)){
            die("500,找不到模型文件");
        }
    }
    
    return $str;
  }

  public function getRecommandModelList(){
    $folder=ROOT."/workspace_recommend/model/";
    $modellist= $this->getModelListByPath($folder);
    return $modellist;
  }

  public function getModelListByPath($folder){
    $filesnames = scandir($folder);
    $ret=array();
    for($i=2;$i<count($filesnames);$i++){

        $filenamearr=explode(".", $filesnames[$i]);
        $path=$folder.$filenamearr[0].".xml";
        $model=$this->getModelByPath($path);//json_decode(json_encode((array) simplexml_load_string($str)), true);
        //print_r($model);
        //exit();
        $filectime=date("Y-m-d H:i",filectime($path));
        $filemtime=date("Y-m-d H:i",filemtime($path));
        $model["modelname"]=$filenamearr[0];
        $model["createdtime"]=$filectime;
        $model["updatedtime"]=$filemtime;
        if($this->checkModelFormat($model)){
          $ret[$filenamearr[0]]=$model;
        }
    }
    //asort($ret,"createdtime");
    return $ret;
  }

  public function getModelList($login,$alias){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    return $this->getModelListByPath($folder);
  }

  public function getModelByPath($path){
    $fp = fopen($path,"r");
    $str = fread($fp,filesize($path));

    $xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
    $model = json_decode(json_encode($xmlstring),true); 

    if($model["options"]["option"][0]==""&&$model["options"]["option"]["name"]!=""){
      $temp=$model["options"]["option"];
      $model["options"]["option"]=array();
      $model["options"]["option"][]=$temp;
    }
    if($model["charts"]["chart"][0]==""&&$model["charts"]["chart"]["name"]!=""){
      $temp=$model["charts"]["chart"];
      $model["charts"]["chart"]=array();
      $model["charts"]["chart"][]=$temp;
    }
    if($model["groups"]["group"]==""){
      //$temp=$model["groups"]["group"];
      //$model["groups"]["group"]=array();
      //$model["groups"]["group"][]=$temp;
    }
    if($model["fields"]["field"][0]==""&&$model["fields"]["field"]["name"]!=""){
      $temp=$model["fields"]["field"];
      $model["fields"]["field"]=array();
      $model["fields"]["field"][]=$temp;
    }
    if($model["javascripts"]["javascript"][0]==""&&$model["javascripts"]["javascript"]["filename"]!=""){
      $temp=$model["javascripts"]["javascript"];
      $model["javascripts"]["javascript"]=array();
      $model["javascripts"]["javascript"][]=$temp;
    }

    for ($i=0; $i < count($model["fields"]["field"]); $i++) { 
      $model["fields"]["field"][$i]["typename"]=$this->keytypename[$model["fields"]["field"][$i]["type"]];
      $model["fields"]["field"][$i]["json"]=json_encode($model["fields"]["field"][$i]);
      if($model["fields"]["field"][$i]["type"]=="select"){
        if($model["fields"]["field"][$i]["options"]["option"][0]==""&&$model["fields"]["field"][$i]["options"]["option"]["name"]!=""){
          $temp=$model["fields"]["field"][$i]["options"]["option"];
          $model["fields"]["field"][$i]["options"]["option"]=array();
          $model["fields"]["field"][$i]["options"]["option"][]=$temp;
        }
      }
    }

    for ($i=0; $i < count($model["options"]["option"]); $i++) { 
      $model["options"]["option"][$i]["json"]=json_encode($model["options"]["option"][$i]);
    }

    for ($i=0; $i < count($model["charts"]["chart"]); $i++) { 
      $model["charts"]["chart"][$i]["json"]=json_encode($model["charts"]["chart"][$i]);
    }

    $model=setArrayNoNull($model);
	//print_r($model);
	//exit;
    return $model;
  }

  public function getModel($login,$alias,$model){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $model=parameter_filter($model);
    $modelname=$model;
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    $path=$folder.$model.".xml";
    $model= $this->getModelByPath($path);

    $model["modelname"]=$modelname;
    return $model;
  }



  public function checkModelFormat($model){
    return !empty($model["name"])&&!empty($model["tablename"])&&!empty($model["fields"]);
  }

  public function createModel($login,$alias,$modelname,$tablename,$name,$method,$srcmodel){
    Global $CONFIG;
    
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $modelname=parameter_filter($modelname);
    $name=parameter_filter($name);
    $method=parameter_filter($method);
    $srcmodel=parameter_filter($srcmodel);

    if($method=="C"){
      $path=ROOT."/workspace_recommend/model/$srcmodel.xml";
      $model=$this->getModelByPath($path);
    }elseif($method=="E"){
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\$srcmodel.xml";
      $model=$this->getModelByPath($path);
    }else{
      $model=array();
    }
    $model["name"]=$name;
    $model["tablename"]=$tablename;
    return $this->saveModel($login,$alias,$modelname,$model);
  }

  public function saveModel($login,$alias,$modelname,$model){
      Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\$modelname.xml";

      $data = array('total_stud' => 500);

      // creating object of SimpleXMLElement
      $xml_data = new SimpleXMLElement('<?xml version="1.0"?><root></root>');

      // function call to convert array to xml
      //$this->array_to_xml($model,"",$xml_data);
      foreach( $model as $key => $value ) {
          if($key=="fields"){
            $fields=$model["fields"]["field"];
            $fieldsnode = $xml_data->addChild("fields");
            foreach ($fields as $field) {
              $fieldnode = $fieldsnode ->addChild("field");
              foreach ($field as $fkey => $fvalue) {
                if($fkey=="options"){

                    $options=$field["options"]["option"];
                    $optionsnode = $fieldnode->addChild("options");
                    foreach ($options as $option) {
                      $optionnode = $optionsnode ->addChild("option");
                      foreach ($option as $fkey => $fvalue) {
                        $optionnode->addChild($fkey,htmlspecialchars($fvalue));
                      }
                    }

                }else {
                  $fieldnode->addChild($fkey,htmlspecialchars($fvalue));
                }
              }
            }
          }elseif ($key=="options") {
            $options=$model["options"]["option"];
            $optionsnode = $xml_data->addChild("options");
            foreach ($options as $option) {
              $optionnode = $optionsnode ->addChild("option");
              foreach ($option as $fkey => $fvalue) {
                $optionnode->addChild($fkey,htmlspecialchars($fvalue));
              }
            }
          }elseif ($key=="groups") {
            $options=$model["groups"]["group"];
            $optionsnode = $xml_data->addChild("groups");
            foreach ($options as $option) {
              $optionsnode ->addChild("group",$option);
            }
          }elseif ($key=="charts") {
            $options=$model["charts"]["chart"];
            $optionsnode = $xml_data->addChild("charts");
            foreach ($options as $option) {
              $optionnode = $optionsnode ->addChild("chart");
              foreach ($option as $fkey => $fvalue) {
                $optionnode->addChild($fkey,htmlspecialchars($fvalue));
              }
            }
          }else{
            $this->addChild($xml_data,$key,$value);
          }
      }

      //saving generated xml file; 
      //echo $path;
      $result = $xml_data->asXML($path);

      $modelmrgpath=$CONFIG['workspace']['path']."\\$login\\$alias\\modelmgr\\$modelname.model.php";
      if(!file_exists($modelmrgpath)){
        $src=ROOT."/workspace_copy/modelmgr/my.model.php";
        copy($src,$modelmrgpath);
        $content = @file_get_contents($modelmrgpath);
            $content = str_replace("{{modelname}}", $modelname, $content);
            $content="<?php
            $content
?>";
           file_put_contents($modelmrgpath, $content);
      }

      $jspath=$CONFIG['workspace']['path']."\\$login\\$alias\\js\\$modelname.js";
      if(!file_exists($jspath)){
        $src=ROOT."/workspace_copy/js/my.js";
        copy($src,$jspath);
      }



      return outResult(0,"保存成功","");
  }

  function addChild(&$node,$key,$value){
            if(trim($value)==""){
                $node->addChild($key);
            }else{
                $node->addChild($key,htmlspecialchars($value));
            }
    }

    function getExecuteSql($login,$alias,$modelname){

      $model=$this->getModel($login,$alias,$modelname);
      $dbname=$login."_".$alias;
	  
      $sql[]="use `$dbname` ;";
      $tablename=parameter_filter($model["tablename"]);

      if($this->dbmgr->checkHave("information_schema.VIEWS","TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename'")||$model["nosave"]=="1"){
        $sql[]="#您使用的表名 $tablename 为视图，不需要生成数据表 ";
        return outResult(0,$sql,"");
      }




      $tablename_description=parameter_filter($model["description"]);
      if(!$this->dbmgr->checkHave("information_schema.TABLES","TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename'")){
        $sql[]="CREATE TABLE `$tablename` (`id` int primary key,`created_date` datetime,`created_user` int,`updated_date` datetime,`updated_user` int) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='$tablename_description';";
      }else{
        $sql[]="#表 $tablename 已经存在";
        $sql[]="alter table `$tablename` comment='$tablename_description';";
      }

      $sql[]="#以下生成数据库表字段";
      foreach ($model["fields"]["field"] as $key => $field) {
        $field_type=parameter_filter($field["type"]);
        $field_key=parameter_filter($field["key"]);
        $field_name=parameter_filter($field["name"]);
        $field_description=$field_name.".\r\n".parameter_filter($field["description"]);
		
        $column_type=$this->keydbtype[$field_type];
		if($field_type=="grid"){
			// $column_type;
		}
        if($field_type=="number"&&$field["isdecimal"]==1){
          $column_type="decimal(12,2)";
        }
        if($field_type=="datetime"&&$field["onlydate"]==1){
          $column_type="date";
        }
		
		$defaultvalue="";
		 if($field_type=="number"){
			 $defaultvalue=" 0 ";
		 }elseif($field_type=="text"||$field_type=="longtext"){
			 $defaultvalue=" '' ";
		 }else{
			 $defaultvalue="";
		 }
		
        if($field_type=="flist"&&!(empty($field["relatetable"]))){
          $column_type="";
          $field_relatetable=parameter_filter($field["relatetable"]);
          $field_tablename=parameter_filter($field["tablename"]);
          if(!$this->dbmgr->checkHave("information_schema.TABLES","TABLE_SCHEMA='$dbname' and TABLE_NAME='$field_relatetable'")){
            $sql[]="CREATE TABLE `$field_relatetable` (`pid` int,`fid` int) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='$field_tablename 的外键关系表';";
          }else{
            $sql[]="#$field_tablename 的外键关系表已经存在";
          }
        }
        if($field_type=="select"){
          foreach ($field["options"]["option"] as  $option) {
            $option_name=parameter_filter($option["name"]);
            $option_value=parameter_filter($option["value"]);
            $field_description.="\r\n $option_name = $option_value";
          }
        }
        if(!empty($column_type)){
		//if($field_type=="grid"){
		//	echo $this->getFieldUpdateStr($dbname,$tablename,$field_key,$column_type,$field_description,$defaultvalue);
		//}
          $sql[]=$this->getFieldUpdateStr($dbname,$tablename,$field_key,$column_type,$field_description,$defaultvalue);
        }


      }

      return outResult(0,$sql,"");

    }

    function getFieldUpdateStr($dbname,$tablename,$column,$column_type,$comment,$defaultvalue){

        $dbname=parameter_filter($dbname);
        $tablename=parameter_filter($tablename);
        $column=parameter_filter($column);
        $column_type=parameter_filter($column_type);
        $comment=parameter_filter($comment);
        //return "TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename' and COLUMN_NAME='$field_key'";
		$default="";
		  if($defaultvalue!=''){
			  $default=" default $defaultvalue";
		  }
		  
      if(!$this->dbmgr->checkHave("information_schema.COLUMNS", "TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename' and COLUMN_NAME='$column'")){
              $sql="ALTER TABLE `$tablename` ADD `$column` $column_type $default COMMENT '$comment';";
          }else{
              $sql="ALTER TABLE `$tablename` MODIFY COLUMN `$column`  $column_type $default COMMENT '$comment' ;";
          }
          return $sql;
    }

    function executeSql($login,$alias,$modelname,$userdbmgr){
      $modelsql=$this->getExecuteSql($login,$alias,$modelname);
      $sqls=$modelsql["result"];
      $rsqls=array();
      foreach ($sqls as  $sql) {
        $sql=trim($sql);
        if($sql!="#"){
          $rsqls[]=$sql;
        }
      }
      $sqls=$rsqls;
      $succ=0;
      $fail=0;
      $failsql=array();
      foreach ($sqls as  $sql) {
        try{
          $userdbmgr->query($sql);
        }catch(Exception $ex){
          $fail++;
          $failsql[]=$sql;
        }
      }
      if($fail>=count($sqls)){
        return outResult(-1,"完全运行失败，请检查模型并重试",$failsql);
      }elseif ($fail>0) {
        return outResult(1,"部分Sql语句执行失败，请复制SQL并连接数据库重试",$failsql);
      }else{
        return outResult(0,"","");
      }

    }

 }
 
 $modelMgr=ModelMgr::getInstance();
 $modelMgr->dbmgr=$dbmgr;




?>