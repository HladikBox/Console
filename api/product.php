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
  include ROOT.'/classes/datamgr/product.cls.php';

  $action=$_REQUEST["action"];
  if($action=="saveproduct"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($productMgr->saveProduct($User["login"],$appinfo["alias"],$_REQUEST["isnew"],$_REQUEST["type"],$_REQUEST["name"],$_REQUEST["summary"],$_REQUEST["description"]));
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>