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
  
  
  $app_type_list=$appMgr->getAppTypeList();
  $smarty->assign("app_type_list",$app_type_list);
  
  $productType=$productMgr->productType();
  $smarty->assign("producttype",$productType);

  $smarty->assign("pt",$_REQUEST["pt"]);
  $smarty->assign("at",$_REQUEST["at"]);
  $smarty->assign("s",$_REQUEST["s"]);
  $smarty->assign("o",$_REQUEST["o"]);

  $marketapplist=$marketMgr->getOnlineAppList();
  for($i=0;$i<count($marketapplist);$i++){
    $marketapplist[$i]["products"]=$marketMgr->getSubmitAppProductListDetail($marketapplist[$i]["app_id"]);
  }
  $smarty->assign("marketapplist",$marketapplist);

  $smarty->display(ROOT.'/templates/market/index.html');
  
?>