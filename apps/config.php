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
  
  $app_type_list=$appMgr->getAppTypeList();
  $smarty->assign("app_type_list",$app_type_list);

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["id"]);
  //print_r($appinfo);
  $appinfo["dev_password"]=md5($User["login"]."_49339");
  $appinfo["dev_remote_password"]=md5($User["login"]."_49339");


  include ROOT.'/classes/datamgr/appbuy.cls.php';

  $buyapps=$appbuyMgr->buyList();
  $smarty->assign("buyapps",$buyapps);


  //print_r($appinfo);
  $smarty->assign("appinfo",$appinfo);
  $smarty->display(ROOT.'/templates/apps/config.html');
?>