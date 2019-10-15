<?php
require '../../include/common.inc.php';
include ROOT.'/include/init.inc.php';
include ROOT.'/include/components.inc.php';
include ROOT.'/classes/datamgr/component.cls.php';

include ROOT.'/classes/datamgr/model.cls.php';
include_once ROOT.'/classes/datamgr/app.cls.php';

$appinfo=$appMgr->getAppInfo($UID,$_REQUEST["app_id"]);
$target=$CONFIG['workspace']['path']."\\".$User["login"]."\\".$appinfo["alias"];
recurse_copy(ROOT."/appcomponents/member/model/",$target."\\model");
recurse_copy(ROOT."/appcomponents/member/modelmgr/",$target."\\modelmgr");
recurse_copy(ROOT."/appcomponents/member/api/",$target."\\api");



outputJSON(outResult("0"));

?>