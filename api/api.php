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
  
  $action=$_REQUEST["action"];
  if($action=="save"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($apiMgr->save($User["login"],$appinfo["alias"],$_REQUEST["apis"]));
  }elseif($action=="downloadsourcecode"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    if($_REQUEST["type"]=="ajax"){
        $folder=$apiMgr->generateAjax($User["login"],$appinfo["alias"]);
    }elseif($_REQUEST["type"]=="typescript"){
        $folder=$apiMgr->generateTypeScript($User["login"],$appinfo["alias"]);
    }elseif($_REQUEST["type"]=="php"){
        $modellist=$modelMgr->getModelList($User["login"],$appinfo["alias"]);
        $folder=$apiMgr->generatePHP($User["login"],$appinfo["alias"],$modellist);
    }
    $zip=new ZipArchive();
    $zipfile=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"]."\\logs\\sourcecode_".$_REQUEST["type"].".zip";
    if($zip->open($zipfile, ZipArchive::OVERWRITE)=== TRUE){
         addFileToZip($folder,"", $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
         $zip->close(); //关闭处理的zip文件
    }


    header("Content-type:text/html;charset=utf-8"); 
    // $file_name="cookie.jpg"; 
    $file_name=$appinfo["alias"]."_".$_REQUEST["type"]."_sourcecode.zip"; 

    $fp=fopen($zipfile,"r"); 
    $file_size=filesize($zipfile); 
    //下载文件需要用到的头 
    Header("Content-type: application/octet-stream"); 
    Header("Accept-Ranges: bytes"); 
    Header("Accept-Length:".$file_size); 
    Header("Content-Disposition: attachment; filename=".$file_name); 
    $buffer=1024; 
    $file_count=0; 
    //向浏览器返回数据 
    while(!feof($fp) && $file_count<$file_size){ 
    $file_con=fread($fp,$buffer); 
    $file_count+=$buffer; 
    echo $file_con; 
    } 
    fclose($fp); 
    exit;
    
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>