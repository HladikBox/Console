<?php

#[Root]
$CONFIG['Title']             = '����';
$CONFIG['URL']="http://www.�ҵ�����.com";
$CONFIG['lang']="zh-cn";//en-us
$CONFIG["SessionName"]="session�ı�ʶ��";
$CONFIG["SupportMultiLanguage"]=false;

$CONFIG['solution_configuration']='release';
$CONFIG['server']		= 'windows';   //windows or linux


#[Database]
$CONFIG['database']['provider']	= 'mysql';  //mssql,sqlsrv
$CONFIG['database']['host']		= '���ݿ��������ַ';  
$CONFIG['database']['database']	= '���ݿ�����';  
$CONFIG['database']['user']		= '��½�˺�';  
$CONFIG['database']['psw']		= '����'; 


#[File upload]
$CONFIG['fileupload']['upload_path']	= "upload";

?>