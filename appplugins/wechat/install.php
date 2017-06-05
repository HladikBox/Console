<?php
require '../../include/common.inc.php';
include ROOT.'/include/init.inc.php';
include ROOT.'/include/plugins.inc.php';
include ROOT.'/classes/datamgr/plugin.cls.php';

include ROOT.'/classes/datamgr/model.cls.php';
include_once ROOT.'/classes/datamgr/app.cls.php';

$appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
$target=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"];
recurse_copy(ROOT."/appplugins/wechat/model/",$target."\\model");
recurse_copy(ROOT."/appplugins/wechat/common/",$target."\\common");

$modelMgr->executeSql($User["login"],$appinfo["alias"],"plugin_wechat",$appMgr->getUserDbMgr());

$pluginArray=array();
$pluginArray["cmsmenu"]="plugin_wechat";
$pluginArray["onlyadmin"]="1";

$pluginMgr->addToPlugXml($User["login"],$appinfo["alias"],"wechat",$pluginArray);


outputJSON(outResult("0"));

?>