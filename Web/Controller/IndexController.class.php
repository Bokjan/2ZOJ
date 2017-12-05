<?php
class IndexController extends Controller{
	function index(){
		//$this->success('OK');
		$this->set('title', 'Welcome to CJOJ');
		$this->display();
	}
	function about()
	{
		$this->set('title', 'About');
		$this->display();
	}
}
