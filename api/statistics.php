<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  include ROOT.'/classes/datamgr/statistics.cls.php';

  
  $action=$_REQUEST["action"];
  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
  if($action=="getdata"){
    
    $tables=$statisticsMgr->getTables($User["login"],$appinfo["alias"]);
    $array["tables"]=$tables;
    $spaces=$statisticsMgr->getSpace($User["login"],$appinfo["alias"]);
    $array["spaces"]=$spaces;
    $apioutput=$statisticsMgr->getApiOutputDate($User["login"],$appinfo["alias"]);
    $array["apioutput"]=$apioutput;
    outputJSON($array);
  }elseif($action=="downloadsourcecode"){
    
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>