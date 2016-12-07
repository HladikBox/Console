<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require 'include/common.inc.php';

  $smarty->assign("client_id",$CONFIG['github']['client_id']);

  $smarty->display(ROOT.'/templates/login.html');
  
?>