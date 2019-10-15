<?php
/*
 * Created on 2010-5-11
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
function encode($str)
{
    return iconv("utf-8", "gbk", $str);
	//return mb_convert_encoding($str,'UTF-8');
}
function parameter_filter($param,$htmlchange=true)
{
	Global $dbmgr;
	$arr=array("'"=>"''");
      $param = trim($param);
	$param = strtr($param,$arr);
	$param = mysqli_real_escape_string($dbmgr->conn,$param);
      if($htmlchange){
         $param = htmlspecialchars($param);
      }
	return $param;
}
function ParentRedirect($url)
{
	//Header("Location: $url");
	echo "<script languate=\"javascript\">";
	echo "parent.location.href='".$url."'";
	echo "</script>";
	exit();
}
function WindowRedirect($url)
{
	//Header("Location: $url");
	echo "<script languate=\"javascript\">";
	echo "window.location.href='".$url."'";
	echo "</script>";
	exit();
}
function ArrayToString($arr){
	$str="";
	foreach($arr as $key=>$value){
		$str.="<$key:$value>
		";
	}
	return $str;
}
/*
 function name：remote_file_exists
 function：valid remote file is exists
 params： $url_file - remote file URL
 return：exists return true，else return false
 */
function remote_file_exists($url_file){
	if(@fclose(@fopen($url_file,"r")))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function getMenuJson($menu){
	
	
$item["current"]=true;
$item["title"]="管理工具";
$item["link"]="#";
foreach ($menu as $val){
	
	$sm=$val["sub_function"];
	$subitemcontent=null;
	foreach ($sm as $vc){
		$url=null;
		$url["name"]=$vc["function_name"];
		$url["urlPathinfo"]=$vc["function_link"];
		$subitemcontent[$vc["function_link"]]=$url;
	}
	$list[$val["function_name"]]=$subitemcontent;
	
	
}
$item["list"]=$list;

return json_encode($item);
}

function ResetNameWithLang($arr,$lang){
	
	if(isset($arr["name"])&&isset($arr["name_".$lang])){
		$arr["name"]=$arr["name_".$lang]."aaa";
	}

	foreach ($arr as $key => $value){
		if(is_array($arr[$key])){
			$arr[$key]=ResetNameWithLang($arr[$key],$lang);
		}
	}
	return $arr;

}

function outputJson($result){
	//print_r($result);
	//$ar["aa"]="cc";
    echo json_encode($result);
	exit;
}
function recurse_copy($src,$dst) {  // 原目录，复制到的目录
       

        $dir=scandir($src);
        //print_r($dir);
        mkdir($dst);
        for($i=2;$i<count($dir);$i++){
            $file=$dir[$i];
          if($file=="."||$file==".."){
            //echo $dir."\\".$filename;
            continue;
          }
            if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }

        }

}


function outResult($code,$message,$return=""){
  $arr=Array();
  $arr["code"]=$code;
  $arr["result"]=$message;
  $arr["return"]=$return;
  return $arr;
}


      function request_post($url , $data ) {
      $postdata = http_build_query(
      $data
      );
      $opts = array('http' =>
      array(
      'method'  => 'POST',
      'header'  => "Content-type: application/x-www-form-urlencoded;",
      'content' => $postdata
      )
      );
      $context = stream_context_create($opts);
      //echo $context;
      //echo "a";
      $result = file_get_contents($url, false, $context);
      //echo $result;
      return $result;
      }

      function request_get($url) {

      $ch = curl_init();

      $headers = array();
      $headers[] = 'Cache-Control: no-cache';
      $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=utf-8';
      $headers[] = 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0';

      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      $res= curl_exec($ch);
      curl_close($ch);
      //echo $res;
      return $res;
      }
      function getDateStr($date){
            $time=strtotime($date);
            return date("Y-m-d",$time);
      }
      
  function setArrayNoNull($arr){
    foreach($arr as $key=>$value){
        if(is_array($value)){
            if(count($value)==0){
                $arr[$key]="";
            }else{
                $arr[$key]=setArrayNoNull($value);
            }
        }
    }
    return $arr;
  }
function bubbleSort($arr,$key) {
  $len = count($arr);
  for($i = 1; $i < $len; $i++) {
    for($j = 0; $j < $len-$i; $j++) {
      if($arr[$j][$key] > $arr[$j+1][$key]) {
        $tmp = $arr[$j+1];
        $arr[$j+1] = $arr[$j];
        $arr[$j] = $tmp;
      }
    }
  }
  return $arr;
}
     function delDir($path){
        $filesnames = scandir($path);
        for($i=2;$i<count($filesnames);$i++){
            $filename=$filesnames[$i];
            if(is_dir($path."/".$filename)){
                delDir($path."/".$filename);
                rmdir($path."/".$filename);
            }else{
                //echo $path."/".$filename."\r\n";
                unlink($path."/".$filename);
            }
        }
    }

  function addFileToZip($path,$root,$zip,$ignore_folder=array()){

        $filesnames = scandir($path);
        //print_r($filesnames);
        $ret=array();
        for($i=2;$i<count($filesnames);$i++){
            $filename=$filesnames[$i];
            if(is_dir($path."/".$filename)){
                 if(in_array($filename,$ignore_folder)){
                    continue;
                 }
                addFileToZip($path."/".$filename,$root.$filename."/", $zip);
            }else{
                //echo $root."/".$filename."\r\n";
                $zip->addFile($path."/".$filename,$root.$filename);
            }

        }
    }

  function dirsize($dir) { 
    $dirarr=scandir($dir);
    $size=0;
    for($i=2;$i<count($dirarr);$i++){
      $filename=$dirarr[$i];
      if($filename=="."||$filename==".."){
        //echo $dir."\\".$filename;
        continue;
      }
      if(is_dir($dir."\\".$filename)){
        $size+=dirsize($dir."\\".$filename."\\");
      }else{
        $size+=filesize($dir."\\".$filename);
      }
    }

    return $size; 
  } 

  function RedirectDownload($file_name,$downloadfile,$onlydownload=true,$contenttype="application/octet-stream"){
    //header("Content-type:text/html;charset=utf-8"); 
    // $file_name="cookie.jpg"; 

    $fp=fopen($downloadfile,"r"); 
    $file_size=filesize($downloadfile); 
    //下载文件需要用到的头 
    Header("Content-type: $contenttype"); 
    if($onlydownload){
      Header("Accept-Ranges: bytes"); 
      Header("Accept-Length:".$file_size); 
      Header("Content-Disposition: attachment; filename=".$file_name); 
    }
    $buffer=1024; 
    $file_count=0; 
    //向浏览器返回数据 
    while(!feof($fp) && $file_count<$file_size){ 
    $file_con=fread($fp,$buffer); 
    $file_count+=$buffer; 
    echo $file_con; 
    } 
    fclose($fp); 
    exit;
  }
      ?>