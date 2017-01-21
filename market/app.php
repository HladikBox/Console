<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';
  include ROOT.'/classes/datamgr/product.cls.php';
  include ROOT.'/classes/datamgr/market.cls.php';
  include ROOT.'/classes/datamgr/model.cls.php';
  
  $id=$_REQUEST["id"];
  $appinfo=$marketMgr->getMarketApp($id);
  $appinfo["products"]=$marketMgr->getSubmitAppProductListDetail($appinfo["app_id"]);
  $appinfo["model"]=$modelMgr->getModelListByPath(ROOT."\\submit_apps\\".$appinfo["app_id"]."\\model\\");
  //print_r($appinfo);
  //exit;
  $smarty->assign("appinfo",$appinfo);


  $smarty->display(ROOT.'/templates/market/app.html');
  
?>