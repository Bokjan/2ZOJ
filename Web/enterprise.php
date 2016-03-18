<?php
/**
 * EnterPrise Framework
 * @version 0.2.1
 * @author Bokjan Chan
 **/
defined('EP_PATH') or define('EP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
//时间相关
define('TIME',time());
define('EP_START',floatval(str_replace(' ','.',microtime())));
//读取基本方法
require(EP_PATH.'Base/functions.php');
require(EP_PATH.'Base/commons.php');
//注册自动包含类函数
spl_autoload_register('cloader');
//初始化文件列表
cloader();
//初始化配置
$_config=require(EP_PATH.'Conf/conf.php');
C($_config);
/*//初始化语言
$_lang=cookie('lang');
if($_lang==NULL){
	$_lang=C('LANG');
	define('LANG',C('LANG'));
	cookie('lang',C('LANG'),TIME+2592000);
}
elseif($_lang=='zh_cn'){
	define('LANG',$_lang);
}*/
//包含其他框架文件
require(EP_PATH.'Lib/Controller.class.php');
require(EP_PATH.'Lib/Enterprise.class.php');
//require(EP_PATH.'Lib/epdb.class.php');
unset($_config,$_lang);
//启动Session
session_start();
//运行框架
Enterprise::run();
