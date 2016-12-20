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
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new AppMgr();
	}

	private function __construct() {

	}
	
	public  function __destruct ()
	{
		
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
      if(empty($name)||strlen($name)>15){
        return outResult("-1","应用名称不能为空并控制在15个字符以内","appname");
      }
      if($type==0){
        return outResult("-1","请选择应用类型","apptype");
      }
      if(empty($alias)||strlen($alias)>15){
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
      $sql="select a.*,ap.name type_name from tb_app a
      inner join tb_app_type ap on a.type=ap.id
      where user_id=$UID and a.status<>'D' order by created_date desc";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array_all($query);

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

      if(empty($name)||strlen($name)>15){
        return outResult("-1","应用名称不能为空并控制在15个字符以内","appname");
      }
      $sql="select 1 from tb_app where name='$name' and user_id=$UID and status<>'D' and id<>$app_id ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      if($result[0]!=""){
        return outResult("-1","这个应用名称已经用过了","appname");
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

      $sql="update tb_app set name='$name', `type`=$type,alias='$alias' where id=$app_id ";
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

 }
 
 $appMgr=AppMgr::getInstance();
 $appMgr->dbmgr=$dbmgr;




?>