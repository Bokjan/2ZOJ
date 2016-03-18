<?php
define('CROOT', '/mnt/hgfs/Data/contest/');
include 'epdb.php';
//$_GET['action'] = 'addrecord';
//$_GET['hash'] = '4a14241bdf2fc9663f9930a79337ef7c';
switch($_GET['action'])
{
	case 'getuid':
		getuid();
		break;
	case 'info':
		info();
		break;
	case 'upload':
		upload();
		break;
	case 'addrecord':
		addrecord();
		break;
}
function getuid()
{
	$hash = addslashes($_GET['hash']);
	$db = new epdb('session');
	$res = $db->where("hash='{$hash}'")->limit('1')->getField('uid');
	$uid = $res[0]['uid'];
	echo $uid;
}
function info()
{
	$db = new epdb('contest');
	$res = $db->where("id={$_GET['cid']}")->find();
	echo "{$res->id},{$res->title},{$res->start},{$res->end},{$res->problemcount},{$res->problems}";
}
function upload()
{
	//处理改过的BASE64串
	//$code = $_POST['code'];
	$code = str_replace('*', '+', $_POST['code']);
	file_put_contents(CROOT."src/{$_GET['id']}.{$_GET['lang']}", base64_decode($code));
}
function addrecord()
{
	$db = new epdb('consub');
	$res = $db->where("uid={$_GET['uid']} AND cid={$_GET['cid']} AND probname='{$_GET['pn']}'")->find();
	if(NULL != $res)
	{
		echo $res->id;
		return;
	}
	else
	{
		$data = array(
			'uid' => $_GET['uid'],
			'cid' => $_GET['cid'],
			'probname' => $_GET['pn'],
			'lang' => $_GET['lang'],
			'result' => 'u',
			'score' => 0,
		);
		$db->data($data)->add();
	}
	$res = $db->where("uid={$_GET['uid']} AND cid={$_GET['cid']} AND probname='{$_GET['pn']}'")->find();
	echo $res->id;
}