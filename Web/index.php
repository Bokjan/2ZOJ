<?php
//define('_APP_URL', 'http://172.16.12.202/');
define('_APP_URL', '/');
if(empty($_COOKIE['hash']))
{
	header('Location: '._APP_URL.'login.php');
	exit;
}
include('epdb.php');
global $db;
$db=new epdb('session');
$hash=addslashes($_COOKIE['hash']);
$res=$db->where("hash='{$hash}'")->limit('1')->getField('uid');
$uid=0;
if(NULL == $res)
{
	header('Location: '._APP_URL.'login.php');
	exit;
}
else
	$uid=$res[0]['uid'];
$db->table('user');
global $user;
$user=$db->where("id={$uid}")->find();
unset($res);
unset($db);
unset($hash);
unset($uid);
require('enterprise.php');
