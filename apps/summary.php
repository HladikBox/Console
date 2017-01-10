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

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["id"]);
  $smarty->assign("appinfo",$appinfo);

  $tables=$statisticsMgr->getTables($User["login"],$appinfo["alias"]);
  $smarty->assign("tables",$tables);


  $spaces=$statisticsMgr->getSpace($User["login"],$appinfo["alias"]);
  $smarty->assign("spaces",$spaces);
  print_r($spaces);
  exit;

  $smarty->assign("id",$_REQUEST["id"]);


  $smarty->display(ROOT.'/templates/apps/summary.html');
?>