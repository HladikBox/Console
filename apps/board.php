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
  include ROOT.'/classes/datamgr/api.cls.php';
  include ROOT.'/classes/datamgr/version.cls.php';
  include ROOT.'/classes/datamgr/product.cls.php';
  include ROOT.'/classes/datamgr/plugin.cls.php';
  include ROOT.'/classes/datamgr/component.cls.php';

  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["id"]);
  $smarty->assign("appinfo",$appinfo);

  $modellist=$modelMgr->getModelList($User["login"],$appinfo["alias"]);
  //print_r($modellist);
  $smarty->assign("modellist",$modellist);
  $recomm_modellist=$modelMgr->getRecommandModelList();
  $smarty->assign("recomm_modellist",$recomm_modellist);

  $apicreatorlist=$apiMgr->getApiList($User["login"],$appinfo["alias"],$modellist);
  $smarty->assign("apicreatorlist",$apicreatorlist);

  $versionlist=$versionMgr->getVersionList($_REQUEST["id"]);
  $smarty->assign("versionlist",$versionlist);

  $productType=$productMgr->productType();
  $smarty->assign("producttype",$productType);
  $productlist=$productMgr->getProductList($User["login"],$appinfo["alias"]);
  $smarty->assign("productlist",$productlist);

  $pluginlist=$pluginMgr->getPluginList();
  $appPlugInlist=$pluginMgr->getAppPluginList($User["login"],$appinfo["alias"]);
  foreach ($appPlugInlist as $key => $value) {
    for ($i=0; $i < count($pluginlist["plugins"]["plugin"]); $i++) { 
      if($pluginlist["plugins"]["plugin"][$i]["id"]==$key){
        $pluginlist["plugins"]["plugin"][$i]["installed"]=1;
      }
    }
  }
  $smarty->assign("pluginlist",$pluginlist);


  $componentlist=$componentMgr->getComponentList();
  $smarty->assign("componentlist",$componentlist);

  
  $menu=$cmsMgr->getMenu($User["login"],$appinfo["alias"]);
  $smarty->assign("menu",$menu);

  $smarty->display(ROOT.'/templates/apps/board.html');
?>