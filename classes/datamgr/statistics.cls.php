<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class StatisticsMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
  public static $userdbmgr=null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new StatisticsMgr();
	}

	private function __construct() {

	}
	
	public  function __destruct ()
	{
		
	}

  public function getTables($login,$alias){

      $login=parameter_filter($login);
      $alias=parameter_filter($alias);

      $sql="select * from information_schema.TABLES where table_schema='".$login."_".$alias."'
      and table_type like '%TABLE%'";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array_all($query);
      return $result;
  }

 }
 
 $statisticsMgr=StatisticsMgr::getInstance();
 $statisticsMgr->dbmgr=$dbmgr;




?>