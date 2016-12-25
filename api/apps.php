<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  include ROOT.'/classes/datamgr/app.cls.php';

  $action=$_REQUEST["action"];
  if($action=="createapp"){
    outputJson($appMgr->createApp($_REQUEST["name"],$_REQUEST["type"],$_REQUEST["alias"]));
  }elseif ($action=="getappinfo") {
  	outputJson($appMgr->getAppInfo($UID,$_REQUEST["id"]));
  }elseif ($action=="deleteapp") {
  	outputJson($appMgr->deleteApp($UID,$_REQUEST["id"]));
  }elseif($action=="saveconfig"){
    //sleep(2);
    //outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));
    //print_r($_REQUEST);
    outputJSON($appMgr->saveConfig($_REQUEST["app_id"],$_REQUEST));
  }elseif($action=="createdb"){
    sleep(2);
    //outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));
    //print_r($_REQUEST);
    outputJSON($appMgr->createDataBase($_REQUEST["app_id"]));
  }elseif($action=="createdadmintable"){
    sleep(2);
    outputJSON($appMgr->createAdminTable($_REQUEST["app_id"]));
  }elseif($action=="setdbaccount"){
    sleep(2);
    outputJSON($appMgr->setDBAccount($_REQUEST["app_id"]));
  }elseif($action=="setworkspace"){
    sleep(2);
    outputJSON($appMgr->setWorkspace($_REQUEST["app_id"]));
  }elseif($action=="initworkspace"){
    sleep(2);
    outputJSON($appMgr->initWorkspace($_REQUEST["app_id"]));
  }elseif($action=="setworkspaceaccount"){
    sleep(2);
    outputJSON($appMgr->setWorkspaceAccount($_REQUEST["app_id"]));
  }elseif($action=="configdone"){
    sleep(2);
    outputJSON($appMgr->configDone($_REQUEST["app_id"]));
  }elseif($action=="start"){
    sleep(2);
    outputJSON($appMgr->startApp($_REQUEST["app_id"]));
  }

outputJSON(outResult("-1","找不到你要调用的请求","找不到你要调用的请求"));

  
?>