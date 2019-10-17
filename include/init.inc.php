<?php

//login redirect
if(!isset($_SESSION[SESSIONNAME]["user"]))
{
	$_SESSION[SESSIONNAME]["url_request"]="http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	WindowRedirect("/");
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


include ROOT.'/classes/datamgr/app.cls.php';
$apps=$appMgr->getUserApps($UID);
$smarty->assign("apps",$apps);
$smarty->assign("appcount",count($apps));

$onlineappcount=0;
foreach ($apps as $key => $value) {
	if($value["market_status"]=="A"){
		$onlineappcount++;
	}
}
$smarty->assign("onlineappcount",$onlineappcount);

?>