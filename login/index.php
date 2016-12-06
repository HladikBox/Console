<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'../include/init.inc.php';
  
//  include ROOT.'/classes/mgr/wechat.cls.php';
//  $signPackage = $WechatMgr->GetSignPackage();
//  $userInfo = $WechatMgr->getUserBaseInfo();

//  $smarty->assign("wechatsign",$signPackage);
//  $smarty->assign("userinfo",$userInfo);
  
  $smarty->display(ROOT.'/templates/login.html');
  
?>