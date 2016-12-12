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
 }
 
 $appMgr=AppMgr::getInstance();
 $appMgr->dbmgr=$dbmgr;




?>
