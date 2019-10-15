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
  include_once ROOT.'/classes/datamgr/cms.cls.php';

  $action=$_REQUEST["action"];
  if($action=="createapp"){
    outputJson($appMgr->createApp($_REQUEST["name"],$_REQUEST["type"],$_REQUEST["alias"],$_REQUEST["create_type"],$_REQUEST["create_app_id"]));
  }elseif ($action=="getappinfo") {
  	outputJson($appMgr->getAppInfo($UID,$_REQUEST["id"]));
  }elseif ($action=="deleteapp") {
  	outputJson($appMgr->deleteApp($UID,$_REQUEST["id"]));
  }elseif($action=="saveconfig"){
    //sleep(1);
    //outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));
    //print_r($_REQUEST);
    outputJSON($appMgr->saveConfig($_REQUEST["app_id"],$_REQUEST));
  }elseif($action=="createdb"){
    sleep(1);
    //outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));
    //print_r($_REQUEST);
    outputJSON($appMgr->createDataBase($_REQUEST["app_id"]));
  }elseif($action=="createdadmintable"){
    sleep(1);
    outputJSON($appMgr->createAdminTable($_REQUEST["app_id"]));
  }elseif($action=="setdbaccount"){
    sleep(1);
    outputJSON($appMgr->setDBAccount($_REQUEST["app_id"]));
  }elseif($action=="setworkspace"){
    sleep(1);
    outputJSON($appMgr->setWorkspace($_REQUEST["app_id"]));
  }elseif($action=="initworkspace"){
  
 include_once ROOT.'/classes/datamgr/product.cls.php';
    sleep(1);
    outputJSON($appMgr->initWorkspace($_REQUEST["app_id"]));
  }elseif($action=="setworkspaceaccount"){
    sleep(1);
    outputJSON($appMgr->setWorkspaceAccount($_REQUEST["app_id"]));
  }elseif($action=="configdone"){
    sleep(1);
    outputJSON($appMgr->configDone($_REQUEST["app_id"]));
  }elseif($action=="start"){
    outputJSON($appMgr->startApp($_REQUEST["app_id"]));
  }elseif($action=="stop"){
    outputJSON($appMgr->stopApp($_REQUEST["app_id"]));
  }elseif($action=="ontop"){
    outputJSON($appMgr->ontop($_REQUEST["app_id"],$_REQUEST["ontop"]));
  }elseif($action=="submitmenu"){
    
    $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
	
	$res=$cmsMgr->submitMenu($User["login"],$appinfo["alias"],$_REQUEST["menu"]);
	//print_r($res);
    outputJSON($res);
  }

outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>