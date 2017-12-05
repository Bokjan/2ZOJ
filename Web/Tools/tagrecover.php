<?php
include 'epdb.php';

//标签去重
$db = new epdb('tag');
$data = $db->getField('id,pid');
foreach($data as $item)
{
	$item['pid'] = substr($item['pid'], 0, strlen($item['pid']) - 1);
	$pids = explode(',', $item['pid']);
	$cop = count($pids);
	echo "Tag Id = {$item['id']}, \nOriginal Pids = {$cop}, \n";
	$newpids;
	foreach($pids as $pid)
	{
		$newpids[$pid] = 0;
	}
	$newstring = '';
	foreach($newpids as $k => $v)
	{
		$newstring .= $k.',';
	}
	$cnp = count($newpids);
	echo "Operated Pids = {$cnp}. \n\n";
	$db->execute("UPDATE oj_tag SET pid = '{$newstring}' WHERE id = {$item['id']};");
	unset($newpids);
	unset($newstring);
}
 
/*
//重新恢复题目标签
$db = new epdb('tag');
$tagdata = $db->getField('id,pid');
foreach($tagdata as $tag)
{
	$tag['pid'] = substr($tag['pid'], 0, strlen($tag['pid']) - 1);
	$pids = explode(',', $tag['pid']);
	foreach($pids as $singlepid)
	{
		$db->execute("UPDATE oj_problem SET tags=concat(tags,'{$tag['id']},') WHERE id = {$singlepid};");
	}
}
//*/
