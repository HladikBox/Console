<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  include_once ROOT.'/classes/datamgr/app.cls.php';
  include ROOT.'/classes/datamgr/version.cls.php';

  $action=$_REQUEST["action"];
  if($action=="submit"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($versionMgr->submit($_REQUEST["app_id"],$User["login"],$appinfo["alias"],$_REQUEST["comment"],$_REQUEST["is_tag"]));
  }else{
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $result=$versionMgr->submit($_REQUEST["app_id"],$User["login"],$appinfo["alias"],"备份上一个版本，防止回滚失败");
    outputJSON($versionMgr->rollback($_REQUEST["app_id"],$User["login"],$appinfo["alias"],$_REQUEST["version"]));
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>