<?php
include 'epdb.php';
$db = new epdb('problem');
// 更新所有用户的AC数
echo "更新所有用户的积分\n";
$query = "SELECT id, points FROM oj_user WHERE submit > 0;";
$uids = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
foreach ($uids as $item) {
	$uid = $item['id'];
	$op = $item['points'];
	$query = "SELECT SUM(tmp.difficulty) AS points FROM (SELECT oj_submit.uid, oj_submit.pid, oj_problem.difficulty FROM oj_submit INNER JOIN oj_problem ON oj_problem.id = oj_submit.pid  WHERE oj_submit.result='AC' AND oj_submit.uid={$uid} GROUP BY oj_submit.pid) AS tmp";
	$np = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	$np = $np[0]['points'];
	if($np != $op) {
		$query = "UPDATE oj_user SET points={$np} WHERE id={$uid};";
		$db->execute($query);
		echo sprintf("Corrected: {U%d: %d -> %d}\n", $uid, $op, $np);
	}
}
echo "\n";
