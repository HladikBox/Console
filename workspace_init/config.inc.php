

#[Root]
$CONFIG['Title']             = '{{AppName}}';
$CONFIG['URL']="http://cmsdev.app-link.org/{{login}}/{{alias}}";
$CONFIG['lang']="zh-cn";//en-us
$CONFIG["SessionName"]="FooterCMS_{{login}}_{{alias}}";
$CONFIG["SupportMultiLanguage"]=false;

$CONFIG['solution_configuration']='debug';
$CONFIG['server']		= 'windows';   //windows or linux


#[Database]
$CONFIG['database']['provider']	= 'mysql';  //mssql,sqlsrv
$CONFIG['database']['host']		= 'mysql.app-link.org';  
$CONFIG['database']['database']	= '{{login}}_{{alias}}';  
$CONFIG['database']['user']		= '{{login}}';  
$CONFIG['database']['psw']		= '{{db_password}}'; 


#[File upload]
$CONFIG['fileupload']['upload_path']	= "upload";

