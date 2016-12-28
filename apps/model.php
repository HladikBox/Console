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
  include ROOT.'/classes/datamgr/cmsapp.cls.php';

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
  $smarty->assign("appinfo",$appinfo);

  $model=$cmsAppMgr->getModel($User["login"],$appinfo["alias"],$_REQUEST["model"]);
  $smarty->assign("model",$model);

  $smarty->assign("keytypelist",$cmsAppMgr->keytypename);


  $modellist=$cmsAppMgr->getModelList($User["login"],$appinfo["alias"]);

  $smarty->assign("modellist",$modellist);


  $smarty->display(ROOT.'/templates/apps/model.html');
?>