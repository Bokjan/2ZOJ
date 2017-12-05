<?php
include 'epdb.php';
$db = new epdb('problem');
// 更新所有问题的AC数
echo "更新所有问题的AC数\n";
$query = "SELECT id FROM oj_problem;";
$pids = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
foreach ($pids as $pid) {
	$pid = $pid['id'];
	$query = "SELECT accept, submit FROM oj_problem WHERE id={$pid};";
	$ori = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$ori = $ori[0];
	$query = "SELECT count(*) FROM oj_submit WHERE pid={$pid} AND accepted=1";
	$acs = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$acs = $acs[0]['count(*)'];
	$query = "SELECT count(*) FROM oj_submit WHERE pid={$pid}";
	$sbs = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$sbs = $sbs[0]['count(*)'];
	if($ori['accept'] != $acs || $ori['submit'] != $sbs) {
		$query = "UPDATE oj_problem SET accept={$acs}, submit={$sbs} WHERE id={$pid};";
		$db->execute($query);
		echo sprintf("Corrected: {P%d: %d/%d -> %d/%d}\n", $pid, $ori['accept'], $ori['submit'], $acs, $sbs);
	}
}
echo "\n";

// 更新所有用户的AC数
echo "更新所有用户的AC数\n";
$query = "SELECT id FROM oj_user;";
$uids = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
foreach ($uids as $uid) {
	$uid = $uid['id'];
	$query = "SELECT accept, submit FROM oj_user WHERE id={$uid};";
	$ori = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$ori = $ori[0];
	$query = "SELECT count(*) FROM (SELECT count(*) FROM oj_submit WHERE uid={$uid} AND accepted=1 GROUP BY pid) AS TMP";
	$acs = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$acs = $acs[0]['count(*)'];
	$query = "SELECT count(*) FROM oj_submit WHERE uid={$uid}";
	$sbs = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$sbs = $sbs[0]['count(*)'];
	if($ori['accept'] != $acs || $ori['submit'] != $sbs) {
		$query = "UPDATE oj_user SET accept={$acs}, submit={$sbs} WHERE id={$uid};";
		$db->execute($query);
		echo sprintf("Corrected: {U%d: %d/%d -> %d/%d}\n", $uid, $ori['accept'], $ori['submit'], $acs, $sbs);
	}
}
echo "\n";
