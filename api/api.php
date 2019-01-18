<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  include ROOT.'/classes/datamgr/api.cls.php';
  include_once ROOT.'/classes/datamgr/app.cls.php';
  include_once ROOT.'/classes/datamgr/model.cls.php';
  include_once ROOT.'/classes/datamgr/generate.cls.php';
  
  $action=$_REQUEST["action"];
  if($action=="save"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($apiMgr->save($User["login"],$appinfo["alias"],$_REQUEST["apis"]));
  }elseif($action=="downloadsourcecode"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $modellist=$modelMgr->getModelList($User["login"],$appinfo["alias"]);
	//print_r($modellist);
	//exit;
    if($_REQUEST["type"]=="ajax"){
        $folder=$generateMgr->generateAjax($User["login"],$appinfo["alias"]);
    }elseif($_REQUEST["type"]=="typescript"){
        $folder=$generateMgr->generateTypeScript($User["login"],$appinfo["alias"],$modellist);
    }elseif($_REQUEST["type"]=="php"){
        $folder=$generateMgr->generatePHP($User["login"],$appinfo["alias"],$modellist);
    }elseif($_REQUEST["type"]=="csharp"){
        $folder=$generateMgr->generateCSharp($User["login"],$appinfo["alias"],$modellist);
    }elseif($_REQUEST["type"]=="mina"){
        $folder=$generateMgr->generateMINA($User["login"],$appinfo["alias"]);
    }
    $zip=new ZipArchive();
    $zipfile=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\logs\\sourcecode_".$_REQUEST["type"].".zip";
    if($zip->open($zipfile, ZipArchive::OVERWRITE)=== TRUE){
         addFileToZip($folder,"", $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
         $zip->close(); //关闭处理的zip文件
    }


    $file_name=$appinfo["alias"]."_".$_REQUEST["type"]."_sourcecode.zip"; 
    RedirectDownload($file_name,$zipfile);

    
  }elseif ($action=="getapicontent") {
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $filepath=$folder."api\\".$model."\\".$func.".php";

    $content="";
    $lines = @file($filepath);
    $startcontent=false;
    foreach($lines as $val){
      if(trim($val)=='<?php'
        ||trim($val)=='?>'){
        $val="";
      }
      if($startcontent){
          $content.=$val;
      }
      if(substr(trim($val),0,13)=="////starthere"){
          $startcontent=true;
      }
    }

    outputJSON($content);

  }elseif ($action=="getjscode") {
	 $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $filepath=$folder."js\\".$model.".js";
	
	$content="";
    $lines = @file($filepath);
    foreach($lines as $val){
      $content.=$val;
    }
	$content=trim($content);
    outputJSON($content);
  }elseif ($action=="setjscode") {
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $model=$_REQUEST["model"];
    $filepath=$folder."js\\".$model.".js";

    $content=trim($_REQUEST["content"]);
    

    file_put_contents($filepath,$content); 
    
    outputJSON(outResult(0,"success","success"));
  }elseif ($action=="getphpcode") {
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $filepath=$folder."modelmgr\\".$model.".model.php";

    $content="";
    $lines = @file($filepath);
    $startcontent=false;
    foreach($lines as $val){
      
      $content.=$val;
    }

    outputJSON($content);

  }elseif ($action=="setphpcode") {
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $filepath=$folder."modelmgr\\".$model.".model.php";

    $content=trim($_REQUEST["content"]);
    

    file_put_contents($filepath,$content);  
    
    outputJSON(outResult(0,"success","success"));
  }elseif ($action=="setapicontent") {
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $filepath=$folder."api\\".$model."\\".$func.".php";

    $content=trim($_REQUEST["content"]);
    
    copy(ROOT."\\workspace_copy\\api.php",$filepath);

    file_put_contents($filepath,str_replace('////starthere',"////starthere"."\n".$content."\n",file_get_contents($filepath))); 
    
    outputJSON(outResult(0,"success","success"));
  }elseif ($action=="saveapi"){

	$appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $folder=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\";
    $func=$_REQUEST["func"];
    $model=$_REQUEST["model"];
    $description=$_REQUEST["description"];
    $filepath=$folder."api\\".$model."\\".$func.".php";

	mkdir($folder."api\\".$model."\\");

    copy(ROOT."\\workspace_copy\\api.php",$filepath);

    $filepath=$folder."api\\".$model."\\".$func.".md";
	file_put_contents($filepath,$description);

    outputJSON(outResult(0,"success","success"));
  }elseif ($action=="downloaddocument"){
  
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
	$filename=$appinfo["name"];
	$apis=$apiMgr->getOutApiList($User["login"],$appinfo["alias"]);
	
	$ret=$apiMgr->generateDoc($appinfo,$apis);
	echo $ret;
	
	ob_start(); //打开缓冲区  
	header("Cache-Control: public");  
	Header("Content-type: application/octet-stream");  
	Header("Accept-Ranges: bytes");  
	if (strpos($_SERVER["HTTP_USER_AGENT"],'MSIE')) {  
	header('Content-Disposition: attachment; filename='.$filename.'.doc');  
	}else if (strpos($_SERVER["HTTP_USER_AGENT"],'Firefox')) {  
	Header('Content-Disposition: attachment; filename='.$filename.'.doc');  
	} else {  
	header('Content-Disposition: attachment; filename='.$filename.'.doc');  
	}  
	header("Pragma:no-cache");  
	header("Expires:0");  
	ob_end_flush();
	exit;
    //outputJSON($apis);
  }
  

outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));
	
?>