<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  include ROOT.'/classes/datamgr/model.cls.php';
  include_once ROOT.'/classes/datamgr/app.cls.php';

  $action=$_REQUEST["action"];
  if($action=="savemodel"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
    outputJSON($modelMgr->saveModel($User["login"],$appinfo["alias"],$_REQUEST["modelname"],$_REQUEST["model"]));
  }elseif($action=="getexecutesql"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"],$_REQUEST["modelname"]);
    outputJSON($modelMgr->getExecuteSql($User["login"],$appinfo["alias"],$_REQUEST["modelname"]));
  }elseif($action=="executesql"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"],$_REQUEST["modelname"]);
    outputJSON($modelMgr->executeSql($User["login"],$appinfo["alias"],$_REQUEST["modelname"],$appMgr->getUserDbMgr()));
  }elseif($action=="applycommmodel"){
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"],$_REQUEST["modelname"]);
    outputJSON($modelMgr->applyCommModel($User["login"],$appinfo["alias"],$_REQUEST["models"]));
  }


outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>