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


    $this->keydbtype=array();
    $this->keydbtype["text"]="varchar(255)";
    $this->keydbtype["password"]="varchar(255)";
    $this->keydbtype["check"]="char(1)";
    $this->keydbtype["longtext"]="varchar(4000)";
    $this->keydbtype["select"]="varchar(15)";
    $this->keydbtype["html"]="text";
    $this->keydbtype["number"]="int";
    $this->keydbtype["upload"]="varchar(1000)";
    $this->keydbtype["datetime"]="datetime";
    $this->keydbtype["fkey"]="int";
    $this->keydbtype["flist"]="varchar(4000)";
	}
	
	public  function __destruct ()
	{
		
	}
  private function xmlToArray( $xml )
  {
     return json_decode(json_encode((array) simplexml_load_string($xml)), true);
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

  public function getModelList($login,$alias){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    $filesnames = scandir($folder);
    $ret=array();
    for($i=2;$i<count($filesnames);$i++){

        $filenamearr=explode(".", $filesnames[$i]);
        $path=$folder.$filenamearr[0].".xml";
        $fp = fopen($path,"r");
        $str = fread($fp,filesize($path));
        $model=$this->getModel($login,$alias,$filenamearr[0]);//json_decode(json_encode((array) simplexml_load_string($str)), true);
        //print_r($model);
        //exit();
        $filectime=filectime($login,$alias,$path);
        $model["modelname"]=$filenamearr[0];
        $model["createdtime"]=$filectime;
        if($this->checkModelFormat($model)){
          $ret[]=$model;
        }
    }
    //asort($ret,"createdtime");
    return $ret;
  }

  public function getModel($login,$alias,$model){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $model=parameter_filter($model);
    $modelname=$model;
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    $path=$folder.$model.".xml";
    $fp = fopen($path,"r");
    $str = fread($fp,filesize($path));

    $xmlstring = simplexml_load_string($str, 'SimpleXMLElement',  LIBXML_NOBLANKS); 
    $model = json_decode(json_encode($xmlstring),true); 

    if($model["options"]["option"][0]==""&&$model["options"]["option"]["name"]!=""){
      $temp=$model["options"]["option"][0];
      $model["options"]["option"]=array();
      $model["options"]["option"][]=$temp;
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

    for ($i=0; $i < count($model["javascripts"]["javascript"]); $i++) { 
      $model["javascripts"]["javascript"][$i]["json"]=json_encode($model["javascripts"]["javascript"][$i]);
    }
    $model["modelname"]=$modelname;
    $model=setArrayNoNull($model);
    return $model;
  }



  public function checkModelFormat($model){
    return !empty($model["name"])&&!empty($model["tablename"])&&!empty($model["fields"]);
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
          }else{
            $this->addChild($xml_data,$key,$value);
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

    function getExecuteSql($login,$alias,$modelname){

      $model=$this->getModel($login,$alias,$modelname);
      $dbname=$login."_".$alias;
      $sql[]="use `$dbname` ;";
      $tablename=parameter_filter($model["tablename"]);

      if($this->dbmgr->checkHave("information_schema.VIEWS","TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename'")){
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
        if($field_type=="number"&&$field["isdecimal"]==1){
          $column_type="decimal(12,2)";
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
          $sql[]=$this->getFieldUpdateStr($dbname,$tablename,$field_key,$column_type,$field_description);
        }


      }

      return outResult(0,$sql,"");

    }

    function getFieldUpdateStr($dbname,$tablename,$column,$column_type,$comment){

        $dbname=parameter_filter($dbname);
        $tablename=parameter_filter($tablename);
        $column=parameter_filter($column);
        $column_type=parameter_filter($column_type);
        $comment=parameter_filter($comment);
        //return "TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename' and COLUMN_NAME='$field_key'";

      if(!$this->dbmgr->checkHave("information_schema.COLUMNS", "TABLE_SCHEMA='$dbname' and TABLE_NAME='$tablename' and COLUMN_NAME='$column'")){
              $sql="ALTER TABLE `$tablename` ADD `$column` $column_type COMMENT '$comment';";
          }else{
              $sql="ALTER TABLE `$tablename` MODIFY COLUMN `$column`  $column_type COMMENT '$comment' ;";
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