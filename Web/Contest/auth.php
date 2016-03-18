<?php
include 'epdb.php';
function login()
{
	$time = time();
	$db = new epdb('user');
	$username = addslashes($_GET['username']);
	$res = $db->where("name='{$username}'")->getField('id,password,salt');
	if(NULL == $res)
	{
		echo 'Invalid Username';
		return;
	}
	else
	{
		$res = $res[0];
	}
	if(md5($_GET['password'].$res['salt']) == $res['password'])
	{
		$db->table('session');
		$hash = md5($time.$res['salt']);
		$data = array(
			'uid' => $res['id'],
			'hash' => $hash,
			'time' => time(),
		);
		$db->data($data)->add();
		echo $hash;
	}
	else
	{
		echo 'Invalid Password';
	}
}
function whoami()
{
	$hash = addslashes($_GET['hash']);
	$db = new epdb('session');
	$res = $db->where("hash='{$hash}'")->limit('1')->getField('uid');
	$uid = $res[0]['uid'];
	$db->table('user');
	$res = $db->where("id={$uid}")->find();
	echo $res->name;
}
if($_GET['action'] == 'login')
{
	login();
}
if($_GET['action'] == 'whoami')
{
	whoami();
}