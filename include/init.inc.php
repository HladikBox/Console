<?php

//login redirect
if(!isset($_SESSION[SESSIONNAME]["user"]))
{
	$_SESSION[SESSIONNAME]["url_request"]="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	WindowRedirect("/login");
	exit();
}

if(isset($_SESSION[SESSIONNAME]["url_request"]))
{
	$url_request=$_SESSION[SESSIONNAME]["url_request"];
	unset($_SESSION[SESSIONNAME]["url_request"]);
	WindowRedirect($url_request);
	exit();
}

$User=$_SESSION[SESSIONNAME]["user"];
$UID=$User["id"];


$smarty->assign("User",$User);

  include ROOT.'/classes/datamgr/setting.cls.php';
  $Setting=$settingMgr->getSetting();
  if(!isset($_SESSION[SESSIONNAME]["setting"]))
  {
    $_SESSION[SESSIONNAME]["setting"]=$Setting;
  }

$smarty->assign("Setting",$Setting);

?>