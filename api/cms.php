<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';

  include ROOT.'/classes/datamgr/app.cls.php';

  $action=$_REQUEST["action"];
  if($action=="appinfo"){
    outputJSON(outResult(0,'',$appMgr->getAppInfoByLoginAlias($_REQUEST["login"],$_REQUEST["alias"])));
  }

outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>