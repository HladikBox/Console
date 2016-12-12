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
  $apps=$appMgr->getUserApps($UID);

  $smarty->assign("app_type_list",$app_type_list);
  $smarty->assign("apps",$apps);
  $smarty->assign("appcount",count($apps));
  $smarty->assign("app_type_list",$app_type_list);
  
  $smarty->display(ROOT.'/templates/apps/index.html');
  
?>