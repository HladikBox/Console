<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class MemberMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static $webServiceClient = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new MemberMgr();
	}

	private function __construct() {
		
	}
	
	public  function __destruct ()
	{
		
	}

	public function getPosterCreatedMemberId($poster_id){
		$poster_id=$poster_id+0;
		$sql="select created_id from tb_n_poster where id=$poster_id ";
		$query = $this->dbmgr->query($sql);
		$result = $this->dbmgr->fetch_array($query); 

		return $result["created_id"]+0;
	}

	public function loginReg($mobile,$password){
	
		if($mobile==""){
			return	"手机号码不能为空";
		}
		if($password==""){
			return	"密码不能为空";
		}
		

		$mobile=parameter_filter($mobile);
		$password=parameter_filter($password);
		$sql="select id,openid,mobile,password from tb_member where mobile='$mobile'  ";
		$query = $this->dbmgr->query($sql);
		$result = $this->dbmgr->fetch_array_all($query); 
		if(count($result)==0){
			$id=$this->dbmgr->getNewId("tb_member");
			$sql="insert into tb_member (id,openid,mobile,password,name,status,created_date,created_user,updated_date,updated_user) values
			($id,'','$mobile','$password','$mobile','A',".$this->dbmgr->getDate().",-1,".$this->dbmgr->getDate().",-1)";
			$query = $this->dbmgr->query($sql);
		}else{
		//echo $result[0]["password"];
		//echo "!=";
		//echo $password;
			if($result[0]["password"]!=$password){
				return "密码不正确";
			}
		}
		
		$sql="select id,openid,mobile,name,photo,status,created_date,created_user,updated_date,updated_user from tb_member where mobile='$mobile'  ";
		$query = $this->dbmgr->query($sql);
		$result = $this->dbmgr->fetch_array($query); 

		return $result;


	}
 }
 
 $memberMgr=MemberMgr::getInstance();
 $memberMgr->dbmgr=$dbmgr;
 
 
 
 
?>