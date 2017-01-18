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
  include ROOT.'/classes/datamgr/market.cls.php';

  $action=$_REQUEST["action"];
  if($action=="submit"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($marketMgr->submit($User["login"],$appinfo["alias"],$_REQUEST["app_id"],$_REQUEST["remarks"]));
  }elseif($action=="discard"){
    outputJSON($marketMgr->discard());
  }elseif ($action=="submitstatus") {
    outputJSON($marketMgr->getSubmittedApp());
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>