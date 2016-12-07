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


$smarty->assign("User",$User);



?>