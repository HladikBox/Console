<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';
  include_once ROOT.'/classes/datamgr/market.cls.php';

  
  	  $submittedapp=$marketMgr->getSubmittedApp();
      $appinfo= $appMgr->getAppInfo($UID,$submittedapp["app_id"]);
      $smarty->assign("appinfo",$appinfo);
      $smarty->assign("sapp",$submittedapp);
      $smarty->assign("accesscmsapi",$CONFIG["appaccessapi"]);
      $cansubmitapps=array();
      foreach ($apps as $key => $value) {
  	    if($value["submit_status"]==""&&$value["run_status"]!="C"&&$value["market_status"]!="A"){
  		    $cansubmitapps[]=$value;
  	    }
      }
      $smarty->assign("submitapps",$cansubmitapps);
      $smarty->display(ROOT.'/templates/market/submit.html');
  
?>