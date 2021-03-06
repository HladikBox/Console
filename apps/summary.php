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
  if($appinfo["run_status"]=='C'){
    die("请先进行配置");
  }

  $tables=$statisticsMgr->getTables($User["login"],$appinfo["alias"]);
  $smarty->assign("tables",$tables);


  $spaces=$statisticsMgr->getSpace($User["login"],$appinfo["alias"]);
  $total_space=0;
  foreach($spaces as $value){
    $total_space+=$value;
  }
  $smarty->assign("spaces",$spaces);
  $smarty->assign("total_space",$total_space);


  $apioutput=$statisticsMgr->getApiOutputDate($User["login"],$appinfo["alias"]);
  $smarty->assign("apioutput",$apioutput);

  $smarty->assign("id",$_REQUEST["id"]);


  $smarty->display(ROOT.'/templates/apps/summary.html');
?>