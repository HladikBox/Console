<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';
  include ROOT.'/classes/datamgr/appbuy.cls.php';

  $buyapps=$appbuyMgr->buyList();
  $smarty->assign("buyapps",$buyapps);
  
  
  $app_type_list=$appMgr->getAppTypeList();
  $smarty->assign("app_type_list",$app_type_list);
  $smarty->assign("appcount",count($apps));
  
  $smarty->display(ROOT.'/templates/apps/index.html');
  
?>