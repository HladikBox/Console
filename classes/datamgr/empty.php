<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class Mgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new Mgr();
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
 }
 
 $Mgr=Mgr::getInstance();
 $Mgr->dbmgr=$dbmgr;




?>
