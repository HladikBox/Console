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
    $model=$this->setArrayNoNull($model);
    return $model;
  }

  public function setArrayNoNull($arr){
    foreach($arr as $key=>$value){
        if(is_array($value)){
            if(count($value)==0){
                $arr[$key]="";
            }else{
                $arr[$key]=$this->setArrayNoNull($value);
            }
        }
    }
    return $arr;
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


  // function array_to_xml( $data,$upcome, &$xml_data ) {
  //   foreach( $data as $key => $value ) {
  //       if( is_numeric($key) ){
  //           $key = $upcome+; //dealing with <0/>..<n/> issues
  //       }
  //       if( is_array($value) ) {
  //           $subnode = $xml_data->addChild($key);
  //           $this->array_to_xml($value,$key, $subnode);
  //       } else {
  //           $xml_data->addChild("$key",htmlspecialchars("$value"));
  //       }
  //    }
  // }

 }
 
 $cmsAppMgr=CmsAppMgr::getInstance();
 $cmsAppMgr->dbmgr=$dbmgr;




?>