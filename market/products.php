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

  
  $_REQUEST["app_id"]=$_REQUEST["app_id"]+0;
  $appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
  $smarty->assign("appinfo",$appinfo);

  $productlist=$productMgr->getProductListDetail($User["login"],$appinfo["alias"]);
  $smarty->assign("productlist",$productlist);


  $smarty->display(ROOT.'/templates/market/products.html');
  
?>