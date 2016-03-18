<?php
class UserController extends Controller
{
	public function logout()
	{
		setcookie('hash', '', time()-86400,'/');
		header('Location: '.C('APP_URL').'login.php');
	}
	public function preference()
	{
		$this->set('title','Preference Settings');
		$this->display();
	}
	public function setpref()
	{
		if($_POST['lang'] == 'cpp')
			$lang='cpp';
		else
			$lang='c';
		if($_POST['open'] == 'on')
			$share='1';
		else
			$share='0';
		$db=new epdb('user');
		global $user;
		$data=array(
			'lang'=>$lang,
			'share'=>$share,
		);
		$db->where('id='.$user->id)->data($data)->save();
		header('Location: '.U('user/preference'));
	}
	public function opensource()
	{
		global $user;
		$db=new epdb('submit');
		$sql="UPDATE oj_submit SET open=1 where uid={$user->id}";
		$db->execute($sql);
		echo 'ok';
	}
	public function ranklist()
	{
		if(isset($_GET['page']) && !is_numeric($_GET['page']))
			$this->error('INVALID PARAMETER page');
		elseif(isset($_GET['page']))
			$page=$_GET['page'];
		else
			$page=1;
		defined('REC_PER_PAGE') or define('REC_PER_PAGE', 15);
		$db=new epdb('user');
		$maxpage=intval(($db->where('1 = 1')->count() + REC_PER_PAGE - 1) / REC_PER_PAGE);
		$this->set('maxpage', $maxpage);
		if($maxpage<$page)
			$this->error('INVALID PARAMETER');
		$this->set('page', $page);
		$tmp=(($page-1)*REC_PER_PAGE);
		$limit='LIMIT '.$tmp.','.REC_PER_PAGE;
		$sql="SELECT id, name, nick, accept, submit, jointime, mail FROM oj_user ORDER BY accept DESC {$limit}";
		$res=$db->execute($sql)->fetch_all(MYSQLI_ASSOC);
		$this->set('res', $res);
		$this->set('title',"Ranklist Page {$page}");
		$this->display();
	}
	public function marked()
	{
		global $user;
		$this->set('title', 'Marked Problems');
		$db = new epdb('marked');
		$query="
		SELECT
			oj_problem.id,
			oj_problem.title,
		TmpTable.accepted,
			oj_problem.difficulty
		FROM
			oj_marked
		LEFT JOIN oj_problem ON oj_problem.id = oj_marked.pid
		LEFT JOIN (
			(
				SELECT
					oj_submit.accepted,
					oj_submit.uid,
					oj_submit.pid
				FROM
					oj_submit
				WHERE
					oj_submit.uid = {$user->id}
				ORDER BY
					oj_submit.accepted DESC
			) AS TmpTable
		) ON (TmpTable.pid = oj_problem.id)
		WHERE
			oj_marked.uid = {$user->id}
		GROUP BY 
			oj_marked.id
		ORDER BY
			oj_marked.id ASC;
		";
		$res = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
		$this->set('res', $res);
		$this->display();
	}
	public function profile()
	{
		global $user;
		$this->set('title', 'Profile');
		$this->display();
	}
	public function detail()
	{
		if(!is_numeric($_GET['uid']))
			die('Invalid User ID!');
		$db = new epdb('user');
		$res = $db->where('id='.$_GET['uid'])->find();
		$this->set('title', "{$res->name}'s Information");
		$this->set('res', $res);
		$this->display();
	}
}