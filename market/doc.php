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
  $path=ROOT."/submit_apps/".$appinfo["app_id"]."/product/".encode($_REQUEST["product"])."/docs/".encode($_REQUEST["name"]);
  if(!file_exists($path)){
    echo "no file";
    exit;
  }
  RedirectDownload(encode($_REQUEST["name"]),$path);
  
?>