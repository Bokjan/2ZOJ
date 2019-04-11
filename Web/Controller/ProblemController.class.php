<?php
 // ini_set("display_errors", "On");
 // error_reporting(E_ALL | E_STRICT);

class ProblemController extends Controller
{
	public function detail()
	{
		if(!isset($_GET['pid']))
			$this->error("INVALID REQUEST");
		$pid=$_GET['pid'];
		$db=new epdb('problem');
		$res=$db->where('id = '.addslashes($pid))->find();
		if($res == null)
			$this->error('INVALID PARAMETER');
		$this->set('pdata', $res);
		$db->table('user');
		$operator=$db->where('id='.$res->operator_id)->getField('name');
		$this->set('operator', $operator[0]['name']);
		$db->table('submit');
		global $user;
		$status;
		$checker = $db->where("pid={$pid} AND uid={$user->id} AND accepted=1")->limit('1')->find();
		/*20170214 - Tags present Update */
		$ac=$db->where("pid={$pid} AND uid={$user->id} AND score >=60 ")->limit('1')->find();
		if($checker != null)
			$status='<tr><td colspan="2" class="gradient-green center"><i class="icon-ok icon-white"></i> Congratulations!</td></tr>';
		if(empty($status))
		{
			$checker = $db->where("pid={$pid} AND uid={$user->id}")->limit('1')->find();
			if($checker != null)
				$status='<tr><td colspan="2" class="gradient-red center"><i class="icon-remove icon-white"></i> Try Again!</td></tr>';
		}
		if(empty($status))
			$status = '<tr><td colspan="2" class="center muted" >Haven\'t submitted yet.</td></tr>';
		/*20150226 - Tags*/
		$db->table('tag');
		if(!empty($res->tags)){
			$where_clause = '';
			$_end = substr_count($res->tags, ',');
			$_t = explode(',', $res->tags);
			$i = 0;
			for(; $i != $_end - 1; ++$i){
				$where_clause .= "id={$_t[$i]} OR ";
			}
			$where_clause .= "id={$_t[$i]}";
			$tags_a = $db->where($where_clause)->getField('name');
			//var_dump($db->lastSql);
			$this->set('tags', $tags_a);
			//var_dump($tags_a);
		}
		else{
			$this->set('tags', NULL);
		}
		/*Tag*/
		$db->table('tag');
		$this->set('taglist', $db/*->order('id ASC')*/->getField('id,name'));
		/*End Tag*/
		/*****************/
		$this->set('ac', $ac);
		$this->set('status', $status);
		$this->set('title', "P{$res->id} - {$res->title}");
		$this->display();
	}
	public function submit()
	{
		if(!isset($_POST['problem']) || !is_numeric($_POST['problem'])) {
			$this->error('Invalid Request');
		}
		global $user;
		if($user->admin < 0)
		{
			$this->error("Application has not been approved. \n 注册信息尚未通过审核。");
			// die("Application has not been approved. \n 注册信息尚未通过审核。");
		}
		if($_POST['method']!='submit')
			$this->error('INVALID REQUEST');
		if(!file_exists(C('JUDGER_PATH').'p/'.$_POST['problem'].'/conf.ini')){
			echo 'Problem configuration file does not exist.';
			exit;
		}
		$db=new epdb('submit');
		$query = sprintf("SELECT `time` FROM oj_submit WHERE uid=%d ORDER BY id DESC LIMIT 1", $user->id);
		$dup_check = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
		if($dup_check != null && TIME - $dup_check[0]['time'] < 10) { // 10秒内不准重复提交
			// echo $dup_check[0]['time'] - TIME;
			$this->error('You can only submit one solution in every 10 seconds.');
		}
		$data=array();
		if(isset($_POST['public']) && $_POST['public']=='on')
			$data['open']='1';
		$data['uid']=$user->id;
		$data['pid']=$_POST['problem'];
		$data['lang']=addslashes($_POST['language']);
		$data['time']=TIME;
		$db->data($data)->add();
		// var_dump($db->lastSql);
		$res=$db->where("uid={$user->id} AND time=".TIME)->getField('id');
		file_put_contents(C('JUDGER_PATH')."src/{$res[0]['id']}.{$_POST['language']}", $_POST['source']);
		$db->execute("UPDATE oj_user SET submit=submit+1 WHERE id={$user->id}");
		$db->execute("UPDATE oj_problem SET submit=submit+1 WHERE id={$_POST['problem']}");
		header('Location: '.U('solution/record?name='.$user->name));
	}
	public function viewlist()
	{
		global $user;
		if(!isset($_GET['page']))
			$page = 1;
		else
			$page = $_GET['page'];
		if(!isset($_GET['sortby']))
			$_GET['sortby']='nosort';
		if(!isset($_GET['cat']))
			$_GET['cat']='0';
		if(!is_numeric($page))
			$this->error('INVALID PARAMETER');
		//每页题目的数量
		defined('PROB_PER_PAGE') or define('PROB_PER_PAGE', 30);
		$map=array(
			'nosort'=>'oj_problem.id ASC',
			'mostac'=>'oj_problem.accept DESC',
			'leastac'=>'oj_problem.accept ASC',
			'mostsb'=>'oj_problem.submit DESC',
			'leastsb'=>'oj_problem.submit ASC',
			'mostdi'=>'oj_problem.difficulty DESC',
			'leastdi'=>'oj_problem.difficulty ASC'
		);
		$db = new epdb('problem');
		$totalProbNum = 0;
		$idstr = '';
		if($_GET['cat'] == '0'){//未分类列出
			$db->table('problem');
			$totalProbNum = $db->where('1=1')->count();
		}
		else{//有分类列出
			$db->table('tag');
			$idstr = $db->where('id='.$_GET['cat'])->getField('pid');
			$idstr = $idstr[0]['pid'];
			$totalProbNum = substr_count($idstr, ',');
		}
		$this->set("totalProbNum", $totalProbNum);
		$this->set("pageStep", intval($totalProbNum / PROB_PER_PAGE / 15 /*上方最大容纳的页数*/) + 1);
		$maxpage=intval(($totalProbNum + PROB_PER_PAGE - 1) / PROB_PER_PAGE);
		$this->set('maxpage', $maxpage);
		if($maxpage<$page)
			$this->error('INVALID PARAMETER');
		$this->set('page', $page);
		$tmp=(($page-1)*PROB_PER_PAGE);
		$limit=$tmp.','.PROB_PER_PAGE;
		
		$pset;
		$db->table('problem');
		if($_GET['cat'] == '0')//未分类列出
		{
			$query="
				SELECT
					oj_problem.id,
					oj_problem.title,
					oj_problem.accept,
					oj_problem.submit,
					oj_problem.difficulty,
					TmpTable1.accepted as ac,
					TmpTable2.accepted as noac,
					oj_marked.id AS isMarked
				FROM
					oj_problem
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=1
					) AS TmpTable1
				) ON (
					TmpTable1.pid = oj_problem.id
				)
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=0
					) AS TmpTable2
				) ON (
					TmpTable2.pid = oj_problem.id
				)
				LEFT JOIN oj_marked ON oj_marked.pid = oj_problem.id
				AND oj_marked.uid = {$user->id}
				GROUP BY
					oj_problem.id
				ORDER BY
					{$map[$_GET['sortby']]}
				LIMIT {$limit};
			";
			$pset=$db->execute($query)->fetch_all(MYSQLI_ASSOC);
		}
		else
		{
			$query="
				SELECT
					oj_problem.id,
					oj_problem.title,
					oj_problem.accept,
					oj_problem.submit,
					oj_problem.difficulty,
					TmpTable1.accepted as ac,
					TmpTable2.accepted as noac,
					oj_marked.id AS isMarked
				FROM
					oj_problem
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=1
					) AS TmpTable1
				) ON (
					TmpTable1.pid = oj_problem.id
				)
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=0
					) AS TmpTable2
				) ON (
					TmpTable2.pid = oj_problem.id
				)
				LEFT JOIN oj_marked ON oj_marked.pid = oj_problem.id
				AND oj_marked.uid = {$user->id}
				WHERE oj_problem.tags LIKE '{$_GET['cat']},%' or  oj_problem.tags LIKE '%,{$_GET['cat']},%' 
				GROUP BY
					oj_problem.id
				ORDER BY
					{$map[$_GET['sortby']]}
				LIMIT {$limit};
			";
			$pset = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
		}
		$this->set('pset', $pset);
		$this->set('title', 'Problem Page '.$page);
		
		/*Tag*/
		$db->table('tag');
		$tags = $db->getField('id,name');
		$this->set('tags', $tags);
		/*End Tag*/
		$this->display();
	}
	public function random()
	{
		$db=new epdb('problem');
		$count=$db->where('1 = 1')->count() + 999;
		$pid=rand(1000, $count);
		header('Location: '.U('problem/detail?pid='.$pid));
	}
	public function search()
	{
		global $user;
		if(!isset($_POST['query']))
			$this->error('INVALID REQUEST');
		$q=addslashes($_POST['query']);
		$db=new epdb('problem');
		$_GET['sortby']='nosort';
		$this->set('page',1);
		$this->set('maxpage',1);
		$this->set('title',"Search result for \"{$q}\"");
		//$pset=$db->where("id='{$q}' OR title LIKE '%{$q}%'")->getField('id,title,accept,submit,difficulty');
		$query="SELECT
					oj_problem.id,
					oj_problem.title,
					oj_problem.accept,
					oj_problem.submit,
					oj_problem.difficulty,
					TmpTable1.accepted as ac,
					TmpTable2.accepted as noac,
					oj_marked.id AS isMarked
				FROM
					oj_problem
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=1
					) AS TmpTable1
				) ON (
					TmpTable1.pid = oj_problem.id
				)
				LEFT JOIN (
					(
						SELECT
							oj_submit.accepted,
							oj_submit.pid
						FROM
							oj_submit
						WHERE
							oj_submit.uid = {$user->id}
							AND oj_submit.accepted=0
					) AS TmpTable2
				) ON (
					TmpTable2.pid = oj_problem.id
				)
				LEFT JOIN oj_marked ON oj_marked.pid = oj_problem.id
				AND oj_marked.uid = {$user->id}
				WHERE oj_problem.id='{$q}' OR oj_problem.title LIKE '%{$q}%'
				GROUP BY oj_problem.id;";
		$pset = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
		if(count($pset) == 1)
		{
			header('Location: '.U('problem/detail?pid=').$pset[0]['id']);
			exit;
		}
		$this->set('pset',$pset);
		$this->display('problem_viewlist');
	}
	public function discussion()
	{
		if(!isset($_GET['pid']) || !is_numeric($_GET['pid']))
			$this->error('INVALID REQUEST');
		else
			$pid=$_GET['pid'];
		$this->set('title', 'Comments of #'.$pid);
		$sql="SELECT oj_comment.text, oj_comment.time, oj_user.name, oj_user.id AS uid FROM oj_comment LEFT JOIN (oj_user) on (oj_user.id=oj_comment.uid) WHERE oj_comment.pid={$pid} ORDER BY oj_comment.id DESC";
		$db=new epdb('comment');
		$res=$db->execute($sql)->fetch_all(MYSQLI_ASSOC);
		$this->set('res',$res);
		$this->display();
	}
	public function commentajax()
	{
		$db=new epdb('comment');
		$data=array(
			'pid'=>$_POST['pid'],
			'uid'=>$_POST['uid'],
			'text'=>addslashes($_POST['comment']),
			'time'=>time(),
		);
		if($db->data($data)->add())
			echo 'ok';
	}
	public function edit()
	{
		global $user;
		if($user->admin != 127)
			$this->error('ACCESS DENIED');
		if(!isset($_GET['pid']))
			$this->error("INVALID REQUEST");
		$pid=$_GET['pid'];
		$db=new epdb('problem');
		$res=$db->where('id = '.addslashes($pid))->find();
		if($res == null)
			$this->error('INVALID PARAMETER');
		$this->set('pdata', $res);
		$this->set('title', "P{$res->id} - {$res->title}");
		$this->display();
	}
	public function add()
	{
		global $user;
		if($user->admin != 127)
			$this->error('ACCESS DENIED');
		$this->set('title','New Problem');
		$this->display();
	}
	public function doedit()
	{
		global $user;
		if($user->admin != 127)
			$this->error('INVALID REQUEST');
		foreach($_POST as $k => $v)
			$_POST[$k] = addslashes($v);
		$data=array(
			'title'=>$_POST['title'],
			'description'=>$_POST['description'],
			'input'=>$_POST['input'],
			'output'=>$_POST['output'],
			'sample_input'=>$_POST['sample_input'],
			'sample_output'=>$_POST['sample_output'],
			'hint'=>$_POST['hint'],
			'source'=>$_POST['source'],
			'tlim'=>$_POST['tlim'],
			'mlim'=>$_POST['mlim'],
			'dataset'=>$_POST['dataset'],
			'difficulty'=>$_POST['difficulty'],
		);
		$db=new epdb('problem');
		if(isset($_GET['exist']))
			$db->where('id='.$_POST['pid'])->data($data)->save();
		else{
			if($db->where("title='{$data['title']}'")->find() != NULL){
				echo 'PROBLEM EXISTS.';
				return;
			}
			$data['operator_id']=$user->id;
			$data['jointime']=time();
			$db->data($data)->add();
		}
		// echo $db->lastSql;
		echo 'ok';
	}
	public function edittags()
	{
		$db = new epdb('tag');
		$diff = $db->where("id={$_POST['tid']}")->find();
		if(strstr($diff->pid, $_POST['pid']))
		{
			header('Location: '.U('problem/detail?pid='.$_POST['pid']));
			return;
		}
		$tag_update = "update oj_tag set pid=concat(pid, '{$_POST['pid']},') where id = {$_POST['tid']};";
		$db->execute($tag_update);
		$problem_update = "update oj_problem set tags=concat(tags, '{$_POST['tid']},') where id = {$_POST['pid']};";
		$db->execute($problem_update);
		header('Location: '.U('problem/detail?pid='.$_POST['pid']));
	}
	public function vsort()
	{
		echo 'ok';
	}
}
