<?php
//这是一个例子
include 'core/common.inc.php';
include 'model/download.php';

$downloadMgr=new DownloadMgr($dbmgr);
$arr=$downloadMgr->_list();
print_r($arr);


include 'model/test.php';
$testMgr=new TestMgr($dbmgr);
$arr=$testMgr->get(1);
print_r($arr);
$req["seq"]=1;
$req["category_id"]=1;
$arr=$downloadMgr->update($req,0);
echo $arr;
$req["category_id"]=2;
$arr=$downloadMgr->update($req,$arr);
echo $arr;

$arr=$downloadMgr->delete($arr);

?>