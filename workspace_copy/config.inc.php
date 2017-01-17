<?php

#[Root]
$CONFIG['Title']             = '标题';
$CONFIG['URL']="http://www.我的域名.com";
$CONFIG['lang']="zh-cn";//en-us
$CONFIG["SessionName"]="session的标识符";
$CONFIG["SupportMultiLanguage"]=false;

$CONFIG['solution_configuration']='release';
$CONFIG['server']		= 'windows';   //windows or linux


#[Database]
$CONFIG['database']['provider']	= 'mysql';  //mssql,sqlsrv
$CONFIG['database']['host']		= '数据库服务器地址';  
$CONFIG['database']['database']	= '数据库名称';  
$CONFIG['database']['user']		= '登陆账号';  
$CONFIG['database']['psw']		= '密码'; 


#[File upload]
$CONFIG['fileupload']['upload_path']	= "upload";

?>