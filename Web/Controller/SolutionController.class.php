<?php
class SolutionController extends Controller
{
	public function source()
	{
		$id=$_GET['id'];
		if(!is_numeric($id))
			$this->error('INVALID PARAMETER');
		$db=new epdb('submit');
		$res=$db->where('id='.$id)->find();
		if($res==null)
			$this->error('INVALID PARAMETER');
		$this->set('data', $res);
		$this->set('title', 'Result of Solution #'.$res->id);
		$map=array(
			'AC' => '<span class="label label-success">Accepted</span>',
			'CE' => '<span class="label">Compile Error</span>',
			'RE' => '<span class="label label-info">Runtime Error</span>',
			'TLE' => '<span class="label label-warning">Time Limit Exceed</span>',
			'MLE' => '<span class="label label-warning">Memory Limit Exceeded</span>',
			'JE' => '<span class="label label-inverse">Judger Error</span>',
			'WA' => '<span class="label label-important">Wrong Answer</span>',
			'u' => '<span class="label">Waiting</span>',
		);
		$resultHtml=$map[$res->result];
		$uid=$res->uid;
		$pid=$res->pid;
		$db->table('user');
		$res=$db->where('id='.$uid)->limit('1')->getField('name');
		$this->set('username', $res[0]['name']);
		$db->table('problem');
		$res=$db->where('id='.$pid)->limit('1')->getField('title,accept,submit,difficulty');
		$this->set('problem', $res[0]);
		$this->set('resultHtml', $resultHtml);
		$this->display();
	}
	public function record()
	{
		global $user;
		if(isset($_GET['page']) && !is_numeric($_GET['page']))
			$this->error('INVALID PARAMETER page');
		elseif(isset($_GET['page']))
			$page=$_GET['page'];
		else
			$page=1;
		if(isset($_GET['pid']) && !is_numeric($_GET['pid']))
			$this->error('INVALID PARAMETER pid');
		else
			$pid=$_GET['pid'];
		if(isset($_GET['name']))
			$suser=urldecode($_GET['name']);
		defined('REC_PER_PAGE') or define('REC_PER_PAGE', 15);
		$db=new epdb('submit');
		$maxpage=intval(($db->where('1 = 1')->count() + REC_PER_PAGE - 1) / REC_PER_PAGE);
		$this->set('maxpage', $maxpage);
		if($maxpage<$page)
			$this->error('INVALID PARAMETER');
		$map=array(
			'AC' => '<span class="label label-success">Accepted</span>',
			'CE' => '<span class="label">Compile Error</span>',
			'RE' => '<span class="label label-info">Runtime Error</span>',
			'TLE' => '<span class="label label-warning">Time Limit Exceed</span>',
			'MLE' => '<span class="label label-warning">Memory Limit Exceeded</span>',
			'JE' => '<span class="label label-inverse">Judger Error</span>',
			'WA' => '<span class="label label-important">Wrong Answer</span>',
			'u' => '<span class="label">Waiting</span>',
		);
		$sql="SELECT oj_user.name, oj_problem.title, oj_submit.id, oj_submit.pid, oj_submit.uid, oj_submit.lang, oj_submit.result, oj_submit.score, oj_submit.timeused, oj_submit.memused, oj_submit.open, oj_submit.time FROM oj_submit LEFT JOIN (oj_user,oj_problem) ON (oj_user.id=uid AND oj_problem.id=pid) WHERE 1 = 1";
		if(!empty($pid))
			$sql.=" AND oj_submit.pid={$pid}";
		if(!empty($suser))
			$sql.=" AND oj_user.name='{$suser}'";
		//$sql.=' AND oj_submit.result!=\'u\'';
		$sql.=' ORDER BY oj_submit.id DESC';
		$this->set('page', $page);
		$tmp=(($page-1)*REC_PER_PAGE);
		$sql.=' LIMIT '.$tmp.','.REC_PER_PAGE;
		$limit = $tmp.','.REC_PER_PAGE;
		$res=$db->execute($sql)->fetch_all(MYSQLI_ASSOC);
		$_count=count($res);
		for($i=0;$i<$_count;$i++)
		{
			$res[$i]['rescode']=$res[$i]['result'];
			$res[$i]['result']=$map[$res[$i]['result']];
			if($res[$i]['lang']=='cpp')
				$res[$i]['lang']='C++11';
			elseif($res[$i]['lang'] == 'c')
				$res[$i]['lang']='C99';
			elseif($res[$i]['lang'] == 'pas')
				$res[$i]['lang'] = 'Pascal';
		}
		$this->set('res', $res);
		$this->set('isEntire', false);
		if(empty($pid) && empty($suser))
		{
			$query="
			SELECT
				Submit2.accepted
			FROM
				oj_submit
			LEFT JOIN (
				SELECT
					pid,
					accepted
				FROM
					oj_submit
				WHERE
					uid = {$user->id}
				AND accepted = 1
			) AS Submit2 ON oj_submit.pid = Submit2.pid
			GROUP BY oj_submit.id
			ORDER BY
				oj_submit.id DESC
			LIMIT {$limit};
			";
			$this->set('isAccepted', $db->execute($query)->fetch_all(MYSQLI_ASSOC));
			$this->set('isEntire', true);
		}
		
		
		$this->set('title','Records');
		$this->display();
	}
	public function openorclose()
	{
		global $user;
		$db=new epdb('submit');
		$ch=$db->where("id={$_POST['id']}")->getField('uid');
		if($user->id==$ch[0]['uid'])
		{
			$data=array(
				'open'=>$_POST['option'],
			);
			$db->where("id={$_POST['id']}")->data($data)->save();
		}
		header('Location: '.U("solution/source?id={$_POST['id']}"));
	}
	public function testdata()
	{
		if(!isset($_GET['id']) || !is_numeric($_GET['id']))
			$this->error('INVALID REQUEST');
		else
			$id=$_GET['id'];
		global $user;
		$db=new epdb('submit');
		$ch=$db->where('id='.$id)->find();
		if($ch==null)
			$this->error('INVALID PARAMETER');
		if($ch->uid!=$user->id)
			$this->error('INVALID ACTION ACCESS');
		if($ch->result=='AC')
			$this->error('NO DATA CAN BE DOWNLOADED');
		$resdata=explode("\n", $ch->resdata);
		$count=count($resdata);
		$i;
		for($i=0;$i<$count;$i++)
		{
			$item=explode(' ', $resdata[$i]);
			if($item[3]!='AC')
				break;
		}
		$datapath=C('JUDGER_PATH').'p/'.$ch->pid.'/';
		$conf=file_get_contents($datapath.'conf.ini');
		$ioprefix=explode("\n",$conf);
		if(count($ioprefix) == 0)
			$ioprefix=explode("\r\n",$conf);
		if(count($ioprefix) == 0)
			$this->error('NO DATA CAN BE DOWNLOADED');
		header('Content-type: text/plain; charset=utf-8');
		header("Content-Disposition: attachment; filename=data{$i}.txt");
		echo $ioprefix[0],$i,'.in';
		echo "\n==========\n";
		@readfile($datapath.$ioprefix[0].$i.'.in');
		echo "\n",$ioprefix[0],$i,'.ans';
		echo "\n==========\n";
		@readfile($datapath.$ioprefix[0].$i.'.ans');
	}
}
