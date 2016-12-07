<?php

//$CONFIG['charset']		= 'utf-8'; 
$CONFIG['URL']="http://console.app-link.org";
$CONFIG["SessionName"]="AppLinkConsole";
$CONFIG["Title"]="APP链结";

$CONFIG['smarty']['rootpath']		= '/'; 
$CONFIG['solution_configuration']='debug';
$CONFIG['server']		= 'windows';   //windows or linux

#[Smarty config]
$CONFIG['smarty']['compile_check']=true; 
$CONFIG['smarty']['debugging']=false; 
$CONFIG['smarty']['caching']=false; 
$CONFIG['smarty']['cache_lifetime']=3600; //second,-1 is always on 


#[log]
$CONFIG['logsavedir'] 		= 'logs/';	
$CONFIG['error_handler'] ="E_ALL";

#[Database]
$CONFIG['database']['provider']	= 'mysql';  //mssql,sqlsrv
$CONFIG['database']['host']		= 'localhost';  
$CONFIG['database']['database']	= 'applink';  
$CONFIG['database']['user']		= 'root';  
$CONFIG['database']['psw']		= 'root'; 



#[File upload]
$CONFIG['fileupload']['upload_path']	= "upload";
$CONFIG['fileupload']['nt_check']		= false;
$CONFIG['fileupload']['ftp_url']		= "127.0.0.1";
$CONFIG['fileupload']['ftp_user']		= "anonymous";
$CONFIG['fileupload']['ftp_password']		= "";
$CONFIG['fileupload']['ftp_dir']		= "/";
$CONFIG['fileupload']['try_time']		= "3";
$CONFIG['fileupload']['try_interval']		= "1";//second


$CONFIG['github']['client_id']	= "5ea64adab67fb0db7c52";
$CONFIG['github']['client_secret']	= "7eaadd7a27c6942b512dfab1e8fce61f8b51af73";



?>