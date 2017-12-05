<?php
function _task_ranking_sort($a, $b) {
	if($a['total'] == $b['total']) {
		return ($a['uid'] < $b['uid']) ? -1 : 1;
	}
	return ($a['total'] > $b['total']) ? -1 : 1;
}
class TaskController extends Controller {
	function overview(){
		$db = new epdb('Task');
		$this->set('res', $db->execute("SELECT * FROM oj_task ORDER BY id DESC;")->fetch_all(MYSQLI_ASSOC));
		$this->set('title', 'Task Overview');
		$this->display();
	}
	function result() {
		if(!isset($_GET['tid']) || !is_numeric($_GET['tid'])) {
			$this->error('Invalid Task ID!');
		}
		// cache check
		// end cache check
		// task fetch
		$db = new epdb('task');
		$task = $db->where('id='.$_GET['tid'])->find();
		if($task == NULL) {
			$this->error('Task not found!');
		}
		$pids = explode(',', $task->problems);
		// end task fetch
		$people = array(); // contestant
		// query submissions
		foreach ($pids as $order => $p) {
			$query = sprintf('SELECT oj_submit.uid, oj_submit.id AS `sid`, oj_user.name, oj_submit.result, oj_submit.score FROM oj_submit LEFT JOIN oj_user ON oj_user.id = oj_submit.uid WHERE oj_submit.pid=%s AND oj_submit.time > %s AND oj_submit.time < %s;', $p, $task->start, $task->end);
			$subs = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
			foreach ($subs as $s) {
				if(!isset($people[$s['uid']])) {
					$people[$s['uid']]['uid'] = $s['uid'];
					$people[$s['uid']]['name'] = $s['name'];
				}
				if(isset($people[$s['uid']][$order])) { // other submissions exist
					if($people[$s['uid']][$order]['s'] >= $s['score']) { // no need to update
						continue;
					}
					// first submission
					if($people[$s['uid']][$order]['sid'] > $s['sid']) {
						$people[$s['uid']][$order]['sid'] = $s['sid'];
						$people[$s['uid']][$order]['first'] = $s['result'].' '.$s['score'];
					}
				}
				// first submission - initialization
				if(!isset($people[$s['uid']][$order]['sid'])) {
					$people[$s['uid']][$order]['sid'] = $s['sid'];
					$people[$s['uid']][$order]['first'] = $s['result'].' '.$s['score'];
				}
				$people[$s['uid']][$order]['s'] = $s['score'];
				$people[$s['uid']][$order]['sid'] = $s['sid'];
				$people[$s['uid']][$order]['r'] = $s['result'];
			}
		}
		// end query submissions
		// calculate total scores
		foreach ($people as $u => $person) {
			$total = 0;
			foreach ($person as $p => $info) {
				if(is_numeric($p)) {
					$total += $info['s'];	
				}
			}
			$people[$u]['total'] = $total;
		}
		// end calculate total scores
		// sort
		usort($people, '_task_ranking_sort');
		// end sort
		// print_r($people);
		// die();
		// render
		$this->set('pids', $pids);
		$this->set('people', $people);
		$this->set('title', 'Result - Task #'.$task->id);
		$this->display();
	}
}
