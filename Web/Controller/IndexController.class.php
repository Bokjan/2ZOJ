<?php
class IndexController extends Controller{
	function index(){
		//$this->success('OK');
		$this->set('title', 'Welcome to 2ZOJ');
		$this->display();
	}
	function about()
	{
		$this->set('title', 'About');
		$this->display();
	}
}
