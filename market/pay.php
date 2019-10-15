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
  include ROOT.'/classes/datamgr/appbuy.cls.php';
  
  $id=$_REQUEST["id"];
  $appinfo=$marketMgr->getMarketApp($id);
  
  $is_paid=$appbuyMgr->checkPaid($id);
  if($is_paid){
      WindowRedirect("/market/paysuccess");
  }else{
    if($appinfo["price"]>0){
      //echo "支付宝接口还没申请";
      //$smarty->display(ROOT.'/templates/market/app.html');
      if($CONFIG['solution_configuration']=="debug"){
        $appbuyMgr->paid($id,$appinfo["price"],$User["login"]);
        WindowRedirect("/market/paysuccess");
      }else{
        echo "支付接口还没申请";
        //$smarty->display(ROOT.'/templates/market/pay.html');
      }
    }else{
      $appbuyMgr->paid($id,0,$User["login"]);
      WindowRedirect("/market/paysuccess");
    }
  }
echo "你是黑客？";

  //$smarty->display(ROOT.'/templates/market/app.html');
  
?>