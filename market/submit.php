<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  $cansubmitapps=array();
  foreach ($apps as $key => $value) {
  	if($value["submit_status"]==""&&$value["run_status"]!="C"){
  		$cansubmitapps[]=$value;
  	}
  }
  $smarty->assign("apps",$cansubmitapps);
  
  
  
  $smarty->display(ROOT.'/templates/market/submit.html');
  
?>