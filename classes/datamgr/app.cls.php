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
    public function createApp($name,$type){
      Global $UID;
      
      $name=parameter_filter($name);
      $type=$type+0;
      if(empty($name)){
        return outResult("-1","应用名称不能为空","appname");
      }
      if($type==0){
        return outResult("-1","请选择应用类型","apptype");
      }
      $sql="select 1 from tb_app where name='$name' and user_id=$UID ";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);
      if($result[0]!=""){
        return outResult("-1","这个应用名称已经用过了","appname");
      }
      $id=$this->dbmgr->getNewId("tb_app");
      $sql="insert into tb_app (id,user_id,name,`type`,created_date) values
      ($id,$UID,'$name',$type,now())";
      $this->dbmgr->query($sql);
      
      return outResult(0,"保存成功",$id);
    }
 }
 
 $appMgr=AppMgr::getInstance();
 $appMgr->dbmgr=$dbmgr;




?>