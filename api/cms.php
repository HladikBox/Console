<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';

  include_once ROOT.'/classes/datamgr/app.cls.php';
  include ROOT.'/classes/datamgr/api.cls.php';
  include ROOT.'/classes/datamgr/market.cls.php';
  
    header('Access-Control-Allow-Origin:*');  
    header('Access-Control-Allow-Methods:POST');  
    header('Access-Control-Allow-Headers:x-requested-with,content-type');  

  $action=$_REQUEST["action"];
  if($action=="appinfo"){
    outputJSON(outResult(0,'',$appMgr->getAppInfoByLoginAlias($_REQUEST["login"],$_REQUEST["alias"])));
  }elseif($action=="apilist"){
    $apilist=$apiMgr->getOutApiList($_REQUEST["login"],$_REQUEST["alias"]);
    outputJSON($apilist);
  }elseif($action=="apicalllog"){
    $apilist=$apiMgr->apiCallLog($_REQUEST["login"],$_REQUEST["alias"],$_REQUEST["model"],$_REQUEST["func"],$_REQUEST["output_data_length"]);
  }elseif($action=="getsubmitcode"){
    $folder=$marketMgr->getSubmitCode($_REQUEST["id"]);

    $zip=new ZipArchive();
    $zipfile=$folder.".zip";
    if(file_exists($zipfile)){
      unlink($zipfile);
    }
    if($zip->open($zipfile, ZipArchive::OVERWRITE)=== TRUE){
         addFileToZip($folder,"", $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
         $zip->close(); //关闭处理的zip文件
    }

    header("Content-type:text/html;charset=utf-8"); 
    // $file_name="cookie.jpg"; 
    $file_name="review_sourcecode_v".$_REQUEST["id"].".zip";

    RedirectDownload($file_name,$zipfile);
    
  }

outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>