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
  
  $app_type_list=$appMgr->getAppTypeList();
  $smarty->assign("app_type_list",$app_type_list);

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["id"]);
  $appinfo["dev_password"]=md5($User["login"]."_".$appinfo["alias"]);
  $appinfo["dev_ftppassword"]=md5($User["login"]."_".$appinfo["alias"]);
  //print_r($appinfo);
  $smarty->assign("appinfo",$appinfo);
  $smarty->display(ROOT.'/templates/apps/config.html');
?>