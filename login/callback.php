<?php

  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';
  
  include ROOT.'/classes/mgr/github.cls.php';
  //$githubMgr->setAccessToken($_REQUEST["code"]);
  echo $githubMgr->getUser();

  
  //$smarty->assign("client_id",$CONFIG['github']['client_id']);
  //$smarty->assign("client_secret",$CONFIG['github']['client_secret']);
  //$smarty->assign("code",$_REQUEST["code"]);

  
  //$smarty->display(ROOT.'/templates/callback.html');
  
?>