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

  $action=$_REQUEST["action"];
  if($action=="save"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($apiMgr->save($User["login"],$appinfo["alias"],$_REQUEST["apis"]));
  }elseif($action=="downloadsourcecode"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    if($_REQUEST["type"]=="web"){
        $folder=$apiMgr->generateWeb($User["login"],$appinfo["alias"]);
    }
    
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>