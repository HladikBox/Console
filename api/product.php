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
  }elseif($action=="upload"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    $type=$_REQUEST["type"];
    $product=$_REQUEST["product"];
    $ftppath=$CONFIG['workspace']['ftp']."/".$User["login"]."/".$appinfo["alias"]."product/".iconv('utf-8', 'gbk', $product)."/".$type;
    WindowRedirect($ftppath);
    exit;
  }elseif($action=="deleteproduct"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($productMgr->deleteProduct($User["login"],$appinfo["alias"],$_REQUEST["name"]));
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>