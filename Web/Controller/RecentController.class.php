<?php
class RecentController extends Controller{
	function overview(){
		$json = @file_get_contents('http://contests.acmicpc.info/contests.json');   
		$rows = json_decode($json, true);
		$this->set('res',$rows);
		$this->set('title', 'Recent Overview');
		$this->display();
	}
}
