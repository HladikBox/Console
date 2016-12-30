<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class AppMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
  public static $userdbmgr=null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new AppMgr();
	}

	private function __construct() {

	}
	
	public  function __destruct ()
	{
		
	}

    function getUserDbMgr(){
      Global $CONFIG;
      if($this->userdbmgr==null){
        $this->userdbmgr = new DbMysql($CONFIG['userdatabase']['host'], $CONFIG['userdatabase']['user'], $CONFIG['userdatabase']['psw']);
      }
      return $this->userdbmgr;
    }

    
    public function getAppTypeList(){
        $sql="select id,name from tb_app_type where status='A' order by order_no";
        $query = $this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array_all($query);
        return $result;
    }
    public function createApp($name,$type,$alias){
      Global $UID,$Setting;
      
      $name=parameter_filter($name);
      $alias=strtolower(parameter_filter($alias));
      $type=$type+0;
      if(empty($name)||mb_strlen($name,'UTF8')>15){
        return outResult("-1","应用名称不能为空并控制在15个字符以内","appname");
      }
      if($type==0){
        return outResult("-1","请选择应用类型","apptype");
      }
      if(empty($alias)||mb_strlen($alias,'UTF8')>15){
        return outResult("-1","应用代号不能为空并控制在15个字符以内","appalias");
      }
      if (preg_match("/^[a-z]/i", $alias)==false) {
        return outResult("-1","应用代号只允许输入英文字符","appalias");
      }
      $sql="select 1 from tb_app where name='$name' and user_id=$UID and status<>'D' ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      if($result[0]!=""){
        return outResult("-1","这个应用名称已经用过了","appname");
      }

      $sql="select 1 from tb_app where `alias`='$alias' and user_id=$UID and status<>'D' ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      if($result[0]!=""){
        return outResult("-1","这个应用代号已经用过了","appalias");
      }

      $sql="select 1 from tb_app where user_id=$UID and status<>'D'";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array_all($query);

      if(count($result)>=$Setting["max_created_apps"]){
        return outResult("-1","你已经超过创建应用的数量了".$Setting["max_created_apps"]."a","appname");
      }
   
      $id=$this->dbmgr->getNewId("tb_app");
      $sql="insert into tb_app (id,user_id,name,`type`,created_date,`status`,run_status,`alias`) values
      ($id,$UID,'$name',$type,now(),'A','C','$alias')";
      $this->dbmgr->query($sql);
      
      return outResult(0,"保存成功",$id);
    }
    public function getUserApps($UID){
      $sql="select a.*,ap.name type_name, case a.run_status when 'C' then '等待配置' when 'P' then '运行中' when 'S' then  '已停止' end as run_status_name  from tb_app a
      inner join tb_app_type ap on a.type=ap.id
      where user_id=$UID and a.status<>'D' order by created_date desc";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array_all($query);

      return $result;
    }

    public function getAppInfoByLoginAlias($login,$alias){
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $sql="select a.*,ai.*,ad.*,aw.*,ap.name type_name from tb_app a
      inner join tb_user_github ug on a.user_id=ug.id
      inner join tb_app_type ap on a.type=ap.id 
      left join tb_app_info ai on a.id=ai.app_id 
      left join tb_app_db ad on a.id=ad.app_id 
      left join tb_app_workspace aw on a.id=aw.app_id 
      where ug.login='$login' and a.alias='$alias' and a.status<>'D' ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      
      return $result;
    }
    public function getAppInfo($UID,$id){
      $sql="select a.*,ai.*,ad.*,aw.*,ap.name type_name from tb_app a
      inner join tb_app_type ap on a.type=ap.id 
      left join tb_app_info ai on a.id=ai.app_id 
      left join tb_app_db ad on a.id=ad.app_id 
      left join tb_app_workspace aw on a.id=aw.app_id 
      where user_id=$UID and a.id=$id ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);

      return $result;
    }
    public function deleteApp($UID,$id){
       $sql="update tb_app set `status`='D' where user_id=$UID and id=$id ";
       $query = $this->dbmgr->query($sql);
    }

    public function saveConfig($app_id,$arr){

      Global $UID ;
      //print_r($arr);
      $app_id=$app_id+0;
      //echo $app_id;
      $name=parameter_filter($arr["name"]);
      $type=parameter_filter($arr["type"]);
      //$alias=parameter_filter($arr["alias"]);

      if(empty($name)||mb_strlen($name,'UTF8')>15){
        return outResult("-1","应用名称不能为空并控制在15个字符以内","appname");
      }
      $sql="select 1 from tb_app where name='$name' and user_id=$UID and status<>'D' and id<>$app_id ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      if($result[0]!=""){
        return outResult("-1","这个应用名称已经用过了","appname");
      }

       if(!$this->dbmgr->checkHave("tb_app","id=$app_id and user_id=$UID")){
          return outResult("-1","找不到提交的应用","appname");
       }

      $description=parameter_filter($arr["description"]);
      $contact_name=parameter_filter($arr["contact_name"]);
      $contact_online=parameter_filter($arr["contact_online"]);
      $contact_mobile=parameter_filter($arr["contact_mobile"]);


      $live_server=parameter_filter($arr["live_server"]);
      $live_dbname=parameter_filter($arr["live_dbname"]);
      $live_login=parameter_filter($arr["live_login"]);
      $live_password=parameter_filter($arr["live_password"]);



      $live_remote_type=parameter_filter($arr["live_remote_type"]);
      $live_remote_server=parameter_filter($arr["live_remote_server"]);
      $live_remote_login=parameter_filter($arr["live_remote_login"]);
      $live_remote_password=parameter_filter($arr["live_remote_password"]);

      $this->dbmgr->begin_trans();

      $sql="update tb_app set name='$name', `type`=$type where id=$app_id ";
      $this->dbmgr->query($sql);

      if($this->dbmgr->checkHave("tb_app_info","app_id=$app_id")){
        $sql="update tb_app_info set description='$description',contact_name='$contact_name',contact_online='$contact_online',contact_mobile='$contact_mobile',updated_date=now() where app_id=$app_id";
      }else{
        $sql="insert into tb_app_info (app_id,description,contact_name,contact_online,contact_mobile,updated_date) values
        ($app_id,'$description','$contact_name','$contact_online','$contact_mobile',now())";
      }
      $this->dbmgr->query($sql);

      if($this->dbmgr->checkHave("tb_app_db","app_id=$app_id")){
        $sql="update tb_app_db set live_server='$live_server',live_dbname='$live_dbname',live_login='$live_login',live_password='$live_password',updated_date=now() where app_id=$app_id";
      }else{
        $sql="insert into tb_app_db (app_id,live_server,live_dbname,live_login,live_password,updated_date) values
        ($app_id,'$live_server','$live_dbname','$live_login','$live_password',now())";
      }
      $this->dbmgr->query($sql);

      if($this->dbmgr->checkHave("tb_app_workspace","app_id=$app_id")){
        $sql="update tb_app_workspace set live_remote_type='$live_remote_type',live_remote_server='$live_remote_server',live_remote_login='$live_remote_login',live_remote_password='$live_remote_password',updated_date=now() where app_id=$app_id";
      }else{
        $sql="insert into tb_app_workspace (app_id,live_remote_type,live_remote_server,live_remote_login,live_remote_password,updated_date) values
        ($app_id,'$live_remote_type','$live_remote_server','$live_remote_login','$live_remote_password',now())";
      }
      $this->dbmgr->query($sql);


      $this->dbmgr->commit_trans();

      return outResult(0,"保存成功","");

    }

    function createDataBase($app_id){
      Global $UID,$User;
      $info=$this->getAppInfo($UID,$app_id);
      //print_r($info);
      $alias=$info["alias"];
      //{{$User.login}}_{{$appinfo.alias}}
      $dbname=$User["login"]."_".$alias;
      if($this->getUserDbMgr()->checkHave("information_schema.SCHEMATA","SCHEMA_NAME='$dbname'")){
        return outResult(1,"数据库".$dbname."已经存在","");
      }else{
        $sql="create database $dbname";
        $this->getUserDbMgr()->query($sql);
      }
      return outResult(0,"保存成功","");
    }


    
    function createAdminTable($app_id){
      Global $UID,$User;
      $info=$this->getAppInfo($UID,$app_id);
      //print_r($info);
      $alias=$info["alias"];
      //{{$User.login}}_{{$appinfo.alias}}
      $dbname=$User["login"]."_".$alias;
      if($this->getUserDbMgr()->checkHave("information_schema.SCHEMATA","SCHEMA_NAME='$dbname'")){
        
        if(!$this->getUserDbMgr()->checkHave("information_schema.TABLES","TABLE_SCHEMA='$dbname' and TABLE_NAME='tb_user'")){
            $sql="CREATE TABLE `$dbname`.`tb_user` (
  `id` int(11) NOT NULL,
  `login_id` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `is_admin` varchar(1) NOT NULL,
  `remarks` varchar(350) NOT NULL,
  `status` varchar(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_user` int(11) NOT NULL,
  `updated_date` datetime NOT NULL,
  `updated_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";   
            $this->getUserDbMgr()->query($sql);
            
            $sql="INSERT INTO `$dbname`.`tb_user` VALUES 
(1,'admin','21232f297a57a5a743894a0e4a801fc3','系统管理员','邮箱','Y','遇到问题，请联系QQ359304951','A',now(),1,now(),1),
(2,'editor','21232f297a57a5a743894a0e4a801fc3','数据编辑员','邮箱','N','遇到问题，请联系QQ359304951','A',now(),1,now(),1);";
            $this->getUserDbMgr()->query($sql);

            return outResult(0,"保存成功","");

        }else{
            return outResult(1,"数据库tb_user表已经创建","");
        }


      }else{
        return outResult(-1,"数据库".$dbname."还没有创建","");
      }
      return outResult(0,"保存成功","");
    }

    function setDBAccount($app_id){
      Global $UID,$User;
      $info=$this->getAppInfo($UID,$app_id);
      //print_r($info);
      $alias=$info["alias"];
      $login=$User["login"];
      //{{$User.login}}_{{$appinfo.alias}}
      $password=md5($login."_49339");
      $dbname=$login."_".$alias;
      if($this->getUserDbMgr()->checkHave("information_schema.SCHEMATA","SCHEMA_NAME='$dbname'")){
        //CREATE USER 'alucard263096'@'mysql.app-link.org' IDENTIFIED BY 'a6c3bc0575df7d9d676890e861130a9a';
        //mysql.user
        if(!$this->getUserDbMgr()->checkHave("mysql.user","User='$login' and Host='%'")){
            $sql="CREATE USER '$login'@'%' IDENTIFIED BY '$password'; ";
            $this->getUserDbMgr()->query($sql);
        }

        $sql="GRANT ALL ON $dbname.* TO '$login'@'%' ";
        $this->getUserDbMgr()->query($sql);

        return outResult(0,"保存成功","");

      }else{
        return outResult(-1,"数据库".$dbname."还没有创建","");
      }
      return outResult(0,"保存成功","");
    }
    
    function setWorkspace($app_id){
      Global $UID,$User,$CONFIG;

      $path=$CONFIG['workspace']['path'];
      $info=$this->getAppInfo($UID,$app_id);
      //print_r($info);
      $alias=$info["alias"];
      $login=$User["login"];
      $subfolder="\\$login\\$alias";
      $path=$path.$subfolder;
      if (is_dir($path)){  
        return outResult(1,"用户文件夹 $subfolder 已经存在","");
      }else{
        mkdir($path,0777,true);
      }
      return outResult(0,"保存成功","");

    }
    function initWorkspace($app_id){
      Global $UID,$User,$CONFIG;


      $path=$CONFIG['workspace']['path'];
      $info=$this->getAppInfo($UID,$app_id);
      //print_r($info);
      $alias=$info["alias"];
      $login=$User["login"];
      $subfolder="\\$login\\$alias";
      $path=$path.$subfolder;
      $password=md5($login."_49339");

      

      if (!is_dir($path)){  
        return outResult(-1,"用户文件夹 $subfolder 不存在","");
      }else{
        //echo $path;
        //print_r(scandir($path)>2);
        if(scandir($path)==false||count(scandir($path))<3){
            recurse_copy(ROOT."/workspace_init",$path);
            $configfile=$path."/config.inc.php";
            $content = @file_get_contents($configfile);
            if(!$content){
              return outResult(-1,"Config文件初始化失败","");
            }
            $content = str_replace("{{AppName}}", $info["name"], $content);
            $content = str_replace("{{login}}", $login, $content);
            $content = str_replace("{{alias}}", $alias, $content);
            $content = str_replace("{{db_password}}", $password, $content);
            $content="<?php
            $content
            ?>";
            if(file_put_contents($configfile, $content)==false){
              return outResult(-1,"Config文件初始化失败","");
            }


        }else{
            return outResult(1,"用户文件夹 $subfolder 已经有内容，不会再进行初始化","");
        }
      }
      return outResult(0,"保存成功","");

    }
    
    function setWorkspaceAccount($app_id){
       return outResult(1,"技术不好，没办法做到动态添加远程账户，将尽快为你设置，请耐心等待","");

    }
    function configDone($app_id){
      Global $UID,$User,$CONFIG;

      $result=$this->getAppInfo($UID,$app_id);

      echo $status=$result["run_status"];
      if($status!="C"){
        return outResult(1,"你早已经完成配置，谢谢你的使用","");
      }else{
        $sql="update tb_app set run_status='P' where id=$app_id";
        $this->dbmgr->query($sql);
      }
      return outResult(0,"保存成功","");
    }
    //function logOperation($app_id,$step_code){

    //}
    function startApp($app_id){
      Global $UID,$User,$CONFIG;
      $result=$this->getAppInfo($UID,$app_id);
      $status=$result["run_status"];
      if($status=="C"){
        return outResult(-1,"你还没有完成配置","");
      }elseif ($status=="P") {
        return outResult(-1,"你的应用已经正在运行中","");
      }
      $sql="update tb_app set run_status='P' where id=$app_id";
      $this->dbmgr->query($sql);
      return outResult(0,"成功","");
    }
    function stopApp($app_id){
      Global $UID,$User,$CONFIG;
      $result=$this->getAppInfo($UID,$app_id);
      $status=$result["run_status"];
      if($status=="C"){
        return outResult(-1,"你还没有完成配置","");
      }elseif ($status=="S") {
        return outResult(-1,"你的应用已经是停止状态","");
      }
      $sql="update tb_app set run_status='S' where id=$app_id";
      $this->dbmgr->query($sql);
      return outResult(0,"成功","");
    }
 }
 
 $appMgr=AppMgr::getInstance();
 $appMgr->dbmgr=$dbmgr;




?>