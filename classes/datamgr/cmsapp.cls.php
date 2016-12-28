<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class CmsAppMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new CmsAppMgr();
	}


  public $keytypename;

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
    asort($ret,"createdtime");
    return $ret;
  }

  public function getModel($login,$alias,$model){
    Global $CONFIG;
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $model=parameter_filter($model);
    $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\model\\";
    $path=$folder.$model.".xml";
    $fp = fopen($path,"r");
    $str = fread($fp,filesize($path));
    $model=json_decode(json_encode((array) simplexml_load_string($str)), true);
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
    return $model;
  }


  public function checkModelFormat($model){
    return !empty($model["name"])&&!empty($model["tablename"])&&!empty($model["fields"]);
  }

 }
 
 $cmsAppMgr=CmsAppMgr::getInstance();
 $cmsAppMgr->dbmgr=$dbmgr;




?>