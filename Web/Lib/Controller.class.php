<?php
/**
 * EnterPrise Framework
 * Controller控制器父类
 * @author Bokjan Chan
 * @version 0.2.0
 */
class Controller{
	private $tVar=array();//模版变量数组
	/**
	 *模版变量赋值
	 *@param mixed $key 变量名/变量数组
	 *@param $value 值
	 *$value不是必须的
	 *若第一个参数是数组，则直接整合
	 *否则进行赋值操作
	 */
	function set($key,$value=''){
		if (is_array($key)) {
			$this->tVar=array_merge($this->tVar,$key);
		} else {
			$this->tVar[$key]=$value;
		}
	}
	/**
	 *成功操作
	 *@param string $message 消息
	 *@param string $jumpUrl (非必须)跳转URL
	 */
	function success($message='',$jumpUrl=''){
		$this->jump($message,$jumpUrl,1);
	}
	/**
	 *错误操作
	 *@param string $message 消息
	 *@param string $jumpUrl (非必须)跳转URL
	 */
	function error($message='',$jumpUrl=''){
		$this->jump($message,$jumpUrl,0);
	}
	/**
	 *跳转
	 *@param string $message 消息
	 *@param string $jumpUrl 跳转URL
	 *@param integer $wait 等待秒数
	 *@param integer $status 状态 0=>失败,1=>成功
	 */
	function jump($message='',$jumpUrl='',$status=1){
		if ($status) {
			//$status=1为真，输出为success版本
			$this->set('message',$message);
			if(!isset($this->waitSecond)){
				$this->set('waitSecond','2');
			}
			if ($jumpUrl=='') {
				$jumpUrl=isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:NULL;
				$this->set('jumpUrl',$jumpUrl);
			}else{
				$this->set('jumpUrl',$jumpUrl);
			}
			$this->display('ep_success.tpl');
		} else {
			$this->set('error',$message);
			if(!isset($this->waitSecond)){
				$this->set('waitSecond','3');
			}
			if ($jumpUrl=='') {
				$this->set('jumpUrl',"javascript:history.back(-1);");
			}else{
				$this->set('jumpUrl',$jumpUrl);
			}
			$this->display('ep_success.tpl');
			exit;
		}
	}
	/**
	 *display输出
	 *@param string $tpl='' 模版
	 *@param boolean $type=true 种类(非必须)
	 */
	function display($tpl=''){
		if ($tpl=='') {
			$sepe=C('TPL_SEPE');
			if ($sepe==NULL) {
				$sepe='_';
			}
			if ($tpl=='') {
				$action=str_replace('Controller', '', ACTION);
				$tpl=$action.$sepe.METHOD.'.html';
			}
		}
		else
			$tpl.='.html';
		$this->render($tpl);
	}
	/**
	 *render输出
	 *@param string $tpl 模版
	 *@param boolean $type 种类(非必须)
	 */
	function render($tpl){
		extract($this->tVar, EXTR_OVERWRITE);
		if(extension_loaded('zlib')&&C('GZIP')){ob_start('ob_gzhandler');}
		header("Content-type:text/html;charset=utf-8");
        /*header('Cache-control: '.C('HTTP_CACHE_CONTROL'));
        header('X-Powered-By: Servlet/2.5 JSP/2.1');
		header('Server: HTTP Load Balancer/1.0');*/
		include_once(EP_PATH.'Tpl/'.$tpl);
		if(extension_loaded('zlib')&&C('GZIP')){ob_end_flush();}
	}
}
?>