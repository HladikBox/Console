<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  $action=$_REQUEST["action"];
  if($action=="testconnect"){
    $type=$_REQUEST["type"];
    if($type=="SSH"){

    }else if($type=="FTP"){
      
    }
    outputJson(outResult(0,""));
  }
 outputJSON(outResult("-1","找不到你要调用的请求",""));



  
?>