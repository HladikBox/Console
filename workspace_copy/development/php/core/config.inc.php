<?php
            

#[Root]
$CONFIG['Title']             = 'aaaaaaaa';
$CONFIG['URL']="http://cmsdev.app-link.org/alucard263096/dogandcat";
$CONFIG['lang']="zh-cn";//en-us
$CONFIG["SessionName"]="FooterCMS_alucard263096_dogandcat";
$CONFIG["SupportMultiLanguage"]=false;

$CONFIG['solution_configuration']='debug';
$CONFIG['server']		= 'windows';   //windows or linux


#[Database]
$CONFIG['database']['provider']	= 'mysql';  //mssql,sqlsrv
$CONFIG['database']['host']		= 'mysql.app-link.org';  
$CONFIG['database']['database']	= 'alucard263096_dogandcat';  
$CONFIG['database']['user']		= 'alucard263096';  
$CONFIG['database']['psw']		= 'a6c3bc0575df7d9d676890e861130a9a'; 


#[File upload]
$CONFIG['fileupload']['upload_path']	= "upload";


            ?>