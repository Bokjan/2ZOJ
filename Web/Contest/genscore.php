<?php
$startTime = time();
define('ROOT', '/home/OJ/contest/');
define('WEB', '/home/OJ/ojweb/Contest/data/');
include 'epdb.php';
$cid = $_GET['cid'];
$db = new epdb('contest');
$conInfo = $db->where("id={$cid}")->find();
//GENERATE CSV
echo "Generating Scores...\n";
$sql = "SELECT
	oj_consub.uid,
	oj_user.name,
	GROUP_CONCAT(oj_consub.probname) AS problems,
	GROUP_CONCAT(oj_consub.score) AS scores,
	SUM(oj_consub.score) AS total
FROM
	oj_consub
LEFT JOIN oj_user ON oj_user.id = oj_consub.uid
WHERE
	cid = {$cid}
GROUP BY oj_consub.uid
ORDER BY total DESC;";
$res = $db->execute($sql)->fetch_all(MYSQLI_ASSOC);
$dataDir = sprintf("%s%04d/", WEB, $cid);
$problems = explode(',', $conInfo->problems);
$fp = fopen($dataDir.'result.csv', 'w');
fprintf($fp, "{$conInfo->title}比赛结果\n");
fprintf($fp, "UID,姓名,");
foreach($problems as $item)
{
	fprintf($fp, "%s,", $item);
}
fprintf($fp, "总分\n");
foreach($res as $item)
{
	fprintf($fp, "%s,%s,", $item['uid'], $item['name']);
	$tmp1 = explode(',', $item['problems']);
	$tmp2 = explode(',', $item['scores']);
	$count = count($tmp1);
	for($i = 0; $i != $conInfo->problemcount; $i++)
	{
		$found = false;
		$j = 0;
		for($j = 0; $j != $count; $j++)
		{
			if($tmp1[$j] == $problems[$i])
			{
				$found = true;
				fprintf($fp, "%s,", $tmp2[$j]);
				break;
			}
		}
		if($found == false)
		{
			fprintf($fp, "0,");
		}
	}
	fprintf($fp, "%d\n", $item['total']);
}
echo "Generating Details...\n";
fprintf($fp, "\n评测详情\n");
$sql = "SELECT oj_consub.id, oj_user.name, oj_consub.probname, oj_consub.lang, oj_consub.result, oj_consub.detail, oj_consub.score FROM oj_consub LEFT JOIN oj_user ON oj_user.id = oj_consub.uid WHERE cid = {$cid};";
$res = $db->execute($sql)->fetch_all(MYSQLI_ASSOC);
fprintf($fp, "序号,姓名,问题,语言,结果,详情,得分\n");
foreach($res as $item)
{
	if($item['lang'] == 'cpp')
	{
		$item['lang'] = 'C++';
	}
	else
	{
		$item['lang'] = 'C';
	}
	fprintf($fp, "%s,%s,%s,%s,%s,%s,%s\n", $item['id'], $item['name'], $item['probname'], $item['lang'], $item['result'], $item['detail'], $item['score']);
}
fclose($fp);
echo "Tasks of Contest #{$cid} successfully finished!\n";
echo 'Time Elapsed: ', time() - $startTime, " Second(s)\n";
