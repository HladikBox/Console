<?php
/*
 * Created on 2012-6-30
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
  require '../include/common.inc.php';
  include ROOT.'/include/init.inc.php';

  $action=$_REQUEST["action"];
  if($action=="testconnect"){
    try{
      //print_r($_REQUEST);
      $conn = mysql_connect($_REQUEST["server"],$_REQUEST["login"],$_REQUEST["password"]);
      //$conn = mysql_connect("127.0.0.1","root","root");
      if(!$conn){
        outputJson(outResult(-1,"连不上数据库服务器"));
      }
      if(!mysql_select_db($_REQUEST["dbname"] , $conn)){
        outputJson(outResult(-1,"连不上数据库实例"));
      }
      $sql="select 1";
      if(!mysql_query($sql)){
        outputJson(outResult(-1,"SQL语句报错"));
      }
      outputJson(outResult(0,""));
    }catch(Ecxeption $ex){
      outputJson(outResult(-1,$ex->getMessage()));
    }
  }


  
?>