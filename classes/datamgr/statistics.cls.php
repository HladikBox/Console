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

      Global $CONFIG;
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
	  $configfile=$CONFIG['workspace']['path']."\\$login\\$alias\\config.inc.php";
	  
	  include $configfile;
	  $dbschema=$CONFIG['database']['database'];
	  
      $sql="select * from information_schema.TABLES where table_schema='$dbschema'
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
          $ret[$filename]=dirsize($path.$filename);
        }else{
          $ret["其它"]+=filesize($path.$filename);
        }
      }
      $login=parameter_filter($login);
      $alias=parameter_filter($alias);
      $sql="select sum(data_length) data from information_schema.TABLES where table_schema='".$login."_".$alias."'";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array($query);

      $ret["数据库"]=$result["data"];

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

  function getApiOutputDate($login,$alias,$days=7){
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);


    $date=date('Y-m-d', strtotime("-$days days"));
    $sql="select call_date, sum(output_data_length) total_data_length from tb_app_calllog
where call_date>='$date' and login='$login' and alias='$alias'
group by call_date
order by call_date";
      $query = $this->dbmgr->query($sql);
      $result = $this->dbmgr->fetch_array_all($query);

      $ret=array();
    for($i=$days;$i>=0;$i--){
      $date=date('Y-m-d', strtotime("-$i days"));
      $ret[$date]=0;
      foreach ($result as $value) {
        //echo "<p>".$date."</p>";
        //echo "<p>".$value["call_date"]."</p>";
        if($date==$value["call_date"]){
          $ret[$date]=$value["total_data_length"];
          break;
        }
      }
    }

    return $ret;


  }

  function getApiCallSummary($login,$alias){
    $login=parameter_filter($login);
    $alias=parameter_filter($alias);
    $sql="
select url,sum(1) callcount,sum(output_data_length) total_data_length from (
select concat(model,'/',func) url,output_data_length from tb_app_calllog 
where login='$login' and alias='$alias'
) a
group by url
    ";
    $query = $this->dbmgr->query($sql);
    $result = $this->dbmgr->fetch_array_all($query);
    return $result;

  }

 }
 
 $statisticsMgr=StatisticsMgr::getInstance();
 $statisticsMgr->dbmgr=$dbmgr;




?>