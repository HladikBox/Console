<?php
/*
 * Created on 2011-2-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */  
 class UserMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new UserMgr();
	}

	private function __construct() {
		
	}
	
	public  function __destruct ()
	{
		
	}
  
  public function getDebugUser(){
     $sql="select * from tb_user_github where id=621332";
		$query = $this->dbmgr->query($sql);
		$result = $this->dbmgr->fetch_array($query);
    return $result;
  }

	public function syncGithubUser($user){
		//print_r($user);
		$id=parameter_filter($user["id"]);
		$avatar_url=parameter_filter($user["avatar_url"]);
		$login=parameter_filter($user["login"]);
		$html_url=parameter_filter($user["html_url"]);
		$repos_url=parameter_filter($user["repos_url"]);
		$url=parameter_filter($user["url"]);
		$type=parameter_filter($user["type"]);
		$name=parameter_filter($user["name"]);
		$company=parameter_filter($user["company"]);
		$location=parameter_filter($user["location"]);
    
    
    $sql="select * from tb_user_github where id=$id";
		$query = $this->dbmgr->query($sql);
		$result = $this->dbmgr->fetch_array_all($query);
    if(count($result)>0){
      $sql="update tb_user_github set html_url='$html_url', repos_url='$repos_url', url='$url',avatar_url='$avatar_url'
      , type='$type', name='$name', company='$company', location='$location',updated_date=now()
      where id=$id";
    }else{
      $sql="insert into tb_user_github (id,login,
      html_url,repos_url,url,avatar_url,
      type,name,company,location,
      authenticated_date,updated_date) values 
      ($id,'$login'
      ,'$html_url','$repos_url','$url','$avatar_url'
      ,'$type','$name','$company','$location'
      ,now(),now())";
    }
    
		$query = $this->dbmgr->query($sql);
	}

 }
 
 $userMgr=UserMgr::getInstance();
 $userMgr->dbmgr=$dbmgr;
 
 
 
 
?>