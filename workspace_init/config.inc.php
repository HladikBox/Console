

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
$CONFIG['fileupload']['oss']	= true;//改为oss上传的话
if($CONFIG['fileupload']['oss']==true){
	$CONFIG['fileupload']['upload_path']="aliyun";//改为qiniu则用七牛的云
	$CONFIG['fileupload']['upload_path']	= "http://applinkupload.oss-cn-shenzhen.aliyuncs.com";
	$CONFIG['fileupload']['accesskeyid']    = "LTAIkBoCGZOPEXFh";
	$CONFIG['fileupload']['accesskeysecret']    = "bN7Gnq1q6l6TDlVK8yQDcjcCzY8sQt";
	$CONFIG['fileupload']['endpoint']    = "oss-cn-shenzhen-internal.aliyuncs.com";
	$CONFIG['fileupload']['bucket']    = "applinkupload";
	$CONFIG['fileupload']['bucket_path']    = "";
}