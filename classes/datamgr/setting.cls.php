<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class SettingMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new SettingMgr();
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

    public function getSetting(){
        $sql="select * from tb_setting";
        $query = $this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);
        return $result;
    }
 }
 
 $settingMgr=SettingMgr::getInstance();
 $settingMgr->dbmgr=$dbmgr;




?>
