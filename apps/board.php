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
  include ROOT.'/classes/datamgr/model.cls.php';
  include ROOT.'/classes/datamgr/cms.cls.php';

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["id"]);
  $smarty->assign("appinfo",$appinfo);

  $modellist=$modelMgr->getModelList($User["login"],$appinfo["alias"]);
  $smarty->assign("modellist",$modellist);
  $recomm_modellist=$modelMgr->getRecommandModelList();
  $smarty->assign("recomm_modellist",$recomm_modellist);

  
  $menu=$cmsMgr->getMenu($User["login"],$appinfo["alias"]);
  $smarty->assign("menu",$menu);

  $smarty->display(ROOT.'/templates/apps/board.html');
?>