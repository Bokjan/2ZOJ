<?php
/**
 * EnterPrise Framework
 * 框架函数库（单字母方法）
 * @version 0.2.0
 * @author Bokjan Chan
 **/
/**
 *快速实例化
 *@param string $class 类名
 *@return object 被实例化的对象
 */
function I($class){
	return new $class();
}
/**
 *项目配置相关
 *@param string $key 配置数组键名(可选) | array 配置值
 *@return mixed 相应配置
 */
function C($key){
	static $ep_config = array();
	//读入配置
	if(is_array($key)){
		return $ep_config=array_merge($ep_config,$key);
	}
	if (isset($key)) {
			if(isset($ep_config[$key])){
			return $ep_config[$key];
		}
		else{
			return NULL;
		}
	} else {
		return $ep_config;
	}
}
/**
 *返回一个URL
 *@param string $string URL信息
 *@return string URL
 */
function U($string){
	$sepe=C('URL_SEPE');
	if($sepe==NULL){
		$sepe='/';
	}
	if(strstr($string, '?')){
		$string=explode('?', $string);
		$class=explode('/', $string[0]);
		$param=explode('&', $string[1]);
		$url=$class[0].$sepe.$class[1].$sepe;
		$i=1;
		$j=count($param);
		foreach ($param as $element) {
			$element=explode('=', $element);
			$url.=$element[0].$sepe.$element[1];
			if($i<$j){
				$url.=$sepe;
			}
			$i++;
		}
		//$url=C('APP_URL').$url;
		$url='/'.$url;
		$url.=C('URL_SUFFIX');
		return $url;
	}
	else{
		$class=explode('/', $string);
		//$url=C('APP_URL').$url;
		$url='/'.$url;
		$url.=$class[0].$sepe.$class[1].C('URL_SUFFIX');
		return $url;
	}
}
/**
 *数据库对象实例化
 *@param string $table 数据表名
 *@return object 数据库操作对象
 */
function M($table=''){
	if($table==''){
		return NULL;
	}
	$ep_obj=new epdb($table);
	return $ep_obj;
}
/**
 *文件缓存方法
 *@param string $key 缓存文件键
 *@param mixed $value 缓存数据
 *@param integer $expire 过期时间(秒)
 *@return NULL | mixed
 */
function F($key,$value=NULL,$expire=NULL){
	if ($value==NULL) {
		$value=unserialize(file_get_contents(EP_PATH.'Base/Cache/'.$key.'.cache'));
		if(!isset($value['expire'])){
			return $value['value'];
		}
		else{
			if ($value['expire']<=time()) {
				return $value['value'];
			} else {
				return NULL;
			}
		}
	} else {
		if ($expire!=NULL) {
			$expire+=time();
			$value=array(
				'value'=>$value,
				'expire'=>$expire
				);
		}
		else{
			$value=array('value'=>$value);
		}
		$value=serialize($value);
		file_put_contents(EP_PATH.'Base/Cache/'.$key.'.cache', $value);
		return;
	}
}
/**
 *语言包相关
 *@param string $key 语句 | array 新包
 *@return string 对应语言
 */
function L($key){
	static $data=array();
	if(is_array($key)){
		return $data=array_merge($data,$key);
	}
	if (isset($key)) {
			if(isset($data[$key])){
			return $data[$key];
		}
		else{
			return NULL;
		}
	} else {
		return $data;
	}
}