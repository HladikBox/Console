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

  
  $_REQUEST["app_id"]=$_REQUEST["app_id"]+0;
  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
  $smarty->assign("appinfo",$appinfo);
  if($_REQUEST["is_submit"]=="Y"){
      $productlist=$marketMgr->getSubmitAppProductListDetail($_REQUEST["app_id"]);
  }else{
      $productlist=$productMgr->getProductListDetail($User["login"],$appinfo["alias"]);
  }
  $smarty->assign("productlist",$productlist);
  $smarty->assign("is_submit",$_REQUEST["is_submit"]);

  $smarty->display(ROOT.'/templates/market/products.html');
  
?>