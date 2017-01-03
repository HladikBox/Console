<?php
/*
 * Created on 2010-5-7
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 class VersionMgr
 {
 	private static $instance = null;
	public static $dbmgr = null;
	public static function getInstance() {
		return self :: $instance != null ? self :: $instance : new VersionMgr();
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

    public function getVersionList($app_id){
        $app_id=$app_id+0;
        $sql="select * from tb_app_version where app_id=$app_id order by committed_date desc";
        
        $query = $this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array_all($query);
        return $result;
    }

    public function submit($app_id,$login,$alias,$comment,$is_tag){
        Global $CONFIG;

        $is_tag=empty($is_tag)?"N":"Y";
        $comment=parameter_filter($comment);
        if(empty($comment)){
            return outResult("-1","请填写版本描述");
        }

        $version=$this->getLatestVersion($app_id);

        
        $login=parameter_filter($login);
        $alias=parameter_filter($alias);
        $folder=$CONFIG['workspace']['path']."\\$login\\$alias\\";

        if (!is_dir($folder."version\\")) mkdir($folder."version\\");

        $zip=new ZipArchive();
        if($zip->open($folder."version\\".$version.".zip", ZipArchive::OVERWRITE)=== TRUE){
            $this->addFileToZip($folder,"", $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
            $zip->close(); //关闭处理的zip文件
        }else{
            return outResult("-1","写入版本失败，请重试!");
        }
        $id=$this->dbmgr->getNewId("tb_app_version");
        $sql="insert into tb_app_version (id,app_id,version,committed_date,comment,is_tag) values ($id,$app_id,$version,now(),'$comment','$is_tag')";
        $this->dbmgr->query($sql);

        $sql="select * from tb_app_version where id=$id";
        $query = $this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);

        return outResult("0","成功",$result);

    }

    public function rollback($app_id,$login,$alias,$version){
         Global $CONFIG;

        $login=parameter_filter($login);
        $alias=parameter_filter($alias);
        $path=$CONFIG['workspace']['path']."\\$login\\$alias";

        $filesnames = scandir($path);
        for($i=2;$i<count($filesnames);$i++){
            $filename=$filesnames[$i];
            if(is_dir($path."/".$filename)){
                 if($filename=="logs"||$filename=="upload"||$filename=="version"){
                    continue;
                 }
                $this->delDir($path."/".$filename);
                rmdir($path."/".$filename);
            }else{
                unlink($path."/".$filename);
            }
        }

        $file=$CONFIG['workspace']['path']."\\$login\\$alias\\version\\$version.zip";

        $zip = new ZipArchive() ; 
        //打开zip文档，如果打开失败返回提示信息 
        if ($zip->open($file) !== TRUE) { 
        return outResult("-1","找不到版本文件，请回滚到其他版本");
        }
        $zip->extractTo($path); 
        //关闭zip文档 
        $zip->close(); 
        
        return outResult("0","成功",$result);
    }

    public function delDir($path){
        $filesnames = scandir($path);
        for($i=2;$i<count($filesnames);$i++){
            $filename=$filesnames[$i];
            if(is_dir($path."/".$filename)){
                $this->delDir($path."/".$filename);
                rmdir($path."/".$filename);
            }else{
                //echo $path."/".$filename."\r\n";
                unlink($path."/".$filename);
            }
        }
    }


    public function getLatestVersion($app_id){
        $app_id=$app_id+0;
        $sql="select ifnull(max(version),0)+1 version from tb_app_version where app_id=$app_id";
        
        $query = $this->dbmgr->query($sql);
        $result = $this->dbmgr->fetch_array($query);
        return $result["version"];
    }

    
  function addFileToZip($path,$root,$zip){

        $filesnames = scandir($path);
        //print_r($filesnames);
        $ret=array();
        for($i=2;$i<count($filesnames);$i++){
            $filename=$filesnames[$i];
            if(is_dir($path."/".$filename)){
                 if($filename=="logs"||$filename=="upload"||$filename=="version"){
                    continue;
                 }
                $this->addFileToZip($path."/".$filename,$root.$filename."/", $zip);
            }else{
                //echo $root."/".$filename."\r\n";
                $zip->addFile($path."/".$filename,$root.$filename);
            }

        }



        //while(($filename=readdir($handler))!==false){
        //    if($filename != "." && $filename != ".."){//文件夹文件名字为'.'和‘..’，不要对他们进行操作
        //        if(is_dir($path."/".$filename)){// 如果读取的某个对象是文件夹，则递归
        //            if($filename=="logs"||$filename=="upload"||$filename=="version"){
        //                return;
        //            }
        //            $this->addFileToZip($path."/".$filename, $zip);
       //         }else{ //将文件加入zip对象
       //         
       //             $zip->addFile($path."/".$filename);
       //         }
       //     }
       // }
       // @closedir($path);
    }

 }
 
 $versionMgr=VersionMgr::getInstance();
 $versionMgr->dbmgr=$dbmgr;




?>
