<?php
include 'epdb.php';
function ShowLatest()
{
	$db = new epdb('contest');
	$res = $db->order('id', 'DESC')->find();
	echo "{$res->id},{$res->title},{$res->start},{$res->end}";
}
function JoinContest($cid)
{
	$db = new epdb('contest');
	$res = $db->where("id={$cid}")->find();
	if($res == NULL)
	{
		echo 'Invalid Parameter';
	}
	$time = time();
	if($res->start > $time)
	{
		echo 'Time Error';
	}
	else
	{
		echo $cid;
	}
}
if($_GET['action'] == 'showlatest')
{
	ShowLatest();
}
if($_GET['action'] == 'join')
{
	if(is_numeric($_GET['cid']))
	{
		JoinContest($_GET['cid']);
	}
	else
	{
		echo 'Invalid Parameter';
	}
}