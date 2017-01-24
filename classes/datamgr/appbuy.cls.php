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
        $this->dbmgr->begin_trans();
        $sql="insert into tb_app_buy (market_app_id,user_id,price,discount,paid_date) values ($market_app_id,$user_id,$price,0,now())";
        $this->dbmgr->query($sql);

        $sql="update tb_market_app set buycount=buycount+1 where id=$market_app_id and user_id=$user_id ";
        $this->dbmgr->query($sql);

        $this->dbmgr->commit_trans();
    }
    public function sellList($market_app_id){
        Global $UID;
        $market_app_id=$market_app_id+0;
        $sql="select a.*,b.login,b.name user_name from tb_app_buy a
        inner join tb_user_github b on a.user_id=b.id
where market_app_id=$market_app_id and user_id=$UID 
order by paid_date";
        $query = $this->dbmgr->query($sql);
        return $this->dbmgr->fetch_array_all($query);
    }

    public function buyList(){
        Global $UID;
        $sql="select c.id, c.app_name,a.price,b.login,c.app_description,a.paid_date,b.name user_name from tb_app_buy a
        inner join tb_market_app c on a.market_app_id=c.id
        inner join tb_user_github b on c.user_id=b.id
where a.user_id=$UID 
order by paid_date";
        $query = $this->dbmgr->query($sql);
        return $this->dbmgr->fetch_array_all($query);
    }
 }
 
 $appbuyMgr=AppbuyMgr::getInstance();
 $appbuyMgr->dbmgr=$dbmgr;




?>
