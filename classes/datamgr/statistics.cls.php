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
  public function getSpace($login,$alias){

      Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $path=$CONFIG['workspace']['path']."\\$login\\$alias\\";

      $ret=array();
      $ret["其它"]=0;
      $dirarr=scandir($path);
      for($i=2;$i<count($dirarr);$i++){
        $filename=$dirarr[$i];
        if(is_dir($path.$filename)){
          $ret[$filename]=$this->dirsize($path.$filename);
        }else{
          $ret["其它"]+=filesize($path.$filename);
        }
      }
      



      return $ret;
  }

  function dirsize($dir) { 
    $dirarr=scandir($dir);
    $size=0;
    for($i=2;$i<count($dirarr);$i++){
      $filename=$dirarr[$i];
        
      if(is_dir($dir."\\".$filename)){
        $size+=$this->dirsize($dir."\\".$filename."\\");
      }else{
        $size+=filesize($dir."\\".$filename);
      }
    }

    return $size; 
  } 

 }
 
 $statisticsMgr=StatisticsMgr::getInstance();
 $statisticsMgr->dbmgr=$dbmgr;




?>