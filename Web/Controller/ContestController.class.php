<?php
class ContestController extends Controller{
	function overview(){
		$db = new epdb('contest');
		$this->set('res', $db->execute("SELECT * FROM oj_contest ORDER BY id DESC LIMIT 11;")->fetch_all(MYSQLI_ASSOC));
		$this->set('title', 'Contests Overview');
		$this->display();
	}
	function upload(){
		global $user;
		$cid = addslashes($_POST['cid']);
		$db = new epdb('consub');
		$cinfo = $db->execute("SELECT * FROM oj_contest where id='{$cid}';")->fetch_all(MYSQLI_ASSOC);
		if($cinfo == NULL){
			die('Invalid Request!');
		}
		$cinfo = $cinfo[0];
		if($cinfo['start'] > TIME || $cinfo['end'] < TIME){
			die('Cannot submit code to this contest at current time!');
		}
		$problems = explode(',', $cinfo['problems']);
		$expfile = explode('.', $_FILES['file']['name']);
		$isprog = false;
		foreach($problems as $item){
			if($expfile[0] == $item){
				$isprog = true;
			}
		}
		if($isprog == false){
			die('Filename is not correct.');
		}
		$langs = array();
		$langs[] = 'c'; $langs[] = 'cpp'; $langs[] = 'pas';
		$islang = false;
		foreach($langs as $item){
			if($expfile[1] == $item){
				$islang = true;
			}
		}
		if($islang == false){
			die('Programming language is not supported.');
		}
		$isfirst = false;
		$checker = $db->execute("SELECT * FROM oj_consub WHERE uid={$user->id} 
			AND cid={$cid} AND probname='{$expfile[0]}';")->fetch_all(MYSQLI_ASSOC);
		if($checker == NULL){
			$isfirst = true;
		}
		if($isfirst){
			$data = array(
				'uid'=>$user->id,
				'cid'=>$cid,
				'probname'=>$expfile[0],
				'lang'=>$expfile[1],
				'result'=>'u',
				'score'=>0
			);
			$db->data($data)->add();
			$checker = $db->execute("SELECT * FROM oj_consub WHERE uid={$user->id} 
				AND cid={$cid} AND probname='{$expfile[0]}';")->fetch_all(MYSQLI_ASSOC);
			$checker = $checker[0];
			file_put_contents(C('CROOT')."src/{$checker['id']}.{$checker['lang']}", file_get_contents($_FILES['file']['tmp_name']));
			echo "Successfully uploaded {$_FILES['file']['name']}!";
		} else {
			$checker = $checker[0];
			file_put_contents(C('CROOT')."src/{$checker['id']}.{$checker['lang']}", file_get_contents($_FILES['file']['tmp_name']));
			echo "Successfully re-uploaded {$_FILES['file']['name']}!";
		}
	}
}
