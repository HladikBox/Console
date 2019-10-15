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
  include ROOT.'/classes/datamgr/model.cls.php';
  include ROOT.'/classes/datamgr/market.cls.php';

  $myapps=$marketMgr->getMyAppList();
  $smarty->assign("myapps",$myapps);

  $smarty->display(ROOT.'/templates/market/apps.html');
  
?>