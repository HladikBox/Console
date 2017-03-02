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
    if($_REQUEST["type"]=="ajax"){
        $folder=$generateMgr->generateAjax($User["login"],$appinfo["alias"]);
    }elseif($_REQUEST["type"]=="typescript"){
        $folder=$generateMgr->generateTypeScript($User["login"],$appinfo["alias"],$modellist);
    }elseif($_REQUEST["type"]=="php"){
        $folder=$generateMgr->generatePHP($User["login"],$appinfo["alias"],$modellist);
    }elseif($_REQUEST["type"]=="csharp"){
        $folder=$generateMgr->generateCSharp($User["login"],$appinfo["alias"],$modellist);
    }
    $zip=new ZipArchive();
    $zipfile=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\logs\\sourcecode_".$_REQUEST["type"].".zip";
    if($zip->open($zipfile, ZipArchive::OVERWRITE)=== TRUE){
         addFileToZip($folder,"", $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
         $zip->close(); //关闭处理的zip文件
    }


    $file_name=$appinfo["alias"]."_".$_REQUEST["type"]."_sourcecode.zip"; 
    RedirectDownload($file_name,$zipfile);

    
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>