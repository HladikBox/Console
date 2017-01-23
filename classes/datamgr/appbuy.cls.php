<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  include_once ROOT.'/classes/datamgr/user.cls.php';
 class AppbuyMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public $productMgr=null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new AppbuyMgr();
	}

	private function __construct() {
		
		//$id=parameter_filter($user["id"]);
        //$query = $this->dbmgr->query($sql);
        //$result = $this->dbmgr->fetch_array_all($query);
        //return $result;

	}
	
	public  function __destruct ()
	{
		
	}

    public function checkPaid($market_app_id){
        Global $UID;
        $market_app_id=$market_app_id+0;
        $ret=$this->dbmgr->checkHave("tb_app_buy","market_app_id=$market_app_id and user_id=$UID");
        return $ret;
    }
    public function paid($market_app_id,$price,$login){
        Global $userMgr;
        $market_app_id=$market_app_id+0;
        $price=$price+0;
        $user=$userMgr->getUserByLogin($login);
        $user_id=$user["id"]+0;
        if($user_id==0){
            throw new Exception("no user find", 1);
        }
        $sql="insert into tb_app_buy (market_app_id,user_id,price,discount,paid_date) values ($market_app_id,$user_id,$price,0,now())";
        $this->dbmgr->query($sql);
    }

 }
 
 $appbuyMgr=AppbuyMgr::getInstance();
 $appbuyMgr->dbmgr=$dbmgr;




?>
