<?php

  require '../include/common.inc.php';
  
  include ROOT.'/classes/mgr/github.cls.php';
  include ROOT.'/classes/datamgr/user.cls.php';
  $githubMgr->setAccessToken($_REQUEST["code"]);
  $user = $githubMgr->getUser();
  $userMgr->syncGithubUser($user);
  $_SESSION[SESSIONNAME]["user"]=$user;
  
  WindowRedirect("/");


  //$smarty->assign("client_id",$CONFIG['github']['client_id']);
  //$smarty->assign("client_secret",$CONFIG['github']['client_secret']);
  //$smarty->assign("code",$_REQUEST["code"]);

  
  //$smarty->display(ROOT.'/templates/callback.html');
  
?>