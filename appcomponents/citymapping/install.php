<?php
require '../../include/common.inc.php';
include ROOT.'/include/init.inc.php';
include ROOT.'/include/components.inc.php';
include ROOT.'/classes/datamgr/component.cls.php';

include ROOT.'/classes/datamgr/model.cls.php';
include_once ROOT.'/classes/datamgr/app.cls.php';
  include ROOT.'/classes/datamgr/cms.cls.php';

$appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
$target=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"];
recurse_copy(ROOT."/appcomponents/citymapping/model/",$target."\\model");
recurse_copy(ROOT."/appcomponents/citymapping/api/",$target."\\api");



$files=array();
$files[]=ROOT."/appcomponents/citymapping/provinces.sql";
$files[]=ROOT."/appcomponents/citymapping/areas.sql";
$files[]=ROOT."/appcomponents/citymapping/cities.sql";
$files[]=ROOT."/appcomponents/citymapping/views.sql";

$userdbmgr=$appMgr->getUserDbMgr();
$userdbmgr->select_db($User["login"]."_".$appinfo["alias"]);
foreach ($files as $value) {
	$file = fopen($value, "r");
	//输出文本中所有的行，直到文件结束为止。
	while(! feof($file))
	{
	 $sql= fgets($file);//fgets()函数从文件指针中读取一行
	 $userdbmgr->query($sql);
	}
	fclose($file);
}



 $menu=$cmsMgr->getMenu($User["login"],$appinfo["alias"]);

$m=array();
$m["module"]="citymappingmgr";
$m["name"]="省份城市地区";
$m["submenus"]["submenu"]=array();
$m["submenus"]["submenu"][]=array("model"=>"provinces","name"=>"省份");
$m["submenus"]["submenu"][]=array("model"=>"cities","name"=>"城市");
$m["submenus"]["submenu"][]=array("model"=>"areas","name"=>"区县");

$havemenu=false;
for($i=0;$i<count($menu["mainmenus"]["mainmenu"]);$i++){
	if($menu["mainmenus"]["mainmenu"][$i]["module"]==$m["module"]){
		$menu["mainmenus"]["mainmenu"][$i]=$m;
		$havemenu=true;
	}
}
if($havemenu==false){
	$menu["mainmenus"]["mainmenu"][]=$m;
}

$cmsMgr->submitMenu($User["login"],$appinfo["alias"],$menu);

outputJSON(outResult("0"));

?>