<?php
/*
 * Created on 2010-5-6
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//set include path and config


define('ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -8)));	// -9 = 0-strlen('includes')-1;
require 'common_function.php';
require 'config.inc.php';

//log start
require 'logger_mgr.cls.php';
define('LOGGER_INFO_FILE', ROOT."/logs/info/log_%y%m%d.txt");
define('LOGGER_ERROR_FILE', ROOT."/logs/error/log_%y%m%d.txt");
define('LOGGER_DEBUG_FILE', ROOT."/logs/debug/log_%y%m%d.txt");
set_error_handler('error_handler');//,$CONFIG['error_handler']

function error_handler($errno,$errmsg,$file,$line,$vars)
{
$errortype=array(1=>"Error",2=>"Warning",4=>"Parsing Error",8=>"Notice",
		16=>"Core Error",32=>"Core Warning",
		64=>"Compile Error",128=>"Compile Warning",
		256=>"User Error",512=>"User Warning",
		1024=>"User Notice",2048=>"Strict Notice");
		if($errno==4||$errno==2)
		{
			//logger_mgr::logInfo("[".$errortype[$errno]."]".$errmsg ."in file ".$file ." line ".$line);
		}
}

require $CONFIG['database']['provider'].'.cls.php';


?>