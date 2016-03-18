<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转中……</title>
<style type="text/css">
<!--
body{background:#29689E;font-family:"微软雅黑";font-size:12px;}.main{background-color:#29689E;line-height:300%;font-size:17px;color:#FFFFFF;width:650px;margin:100px  auto;border-radius:10px;padding:20px 80px;list-style:none;}.main p{font-size:150px;}a:link{color:#FFFFFF;text-decoration:none;}a:visited{color:#FFFFFF;text-decoration:none;}a:hover{color:#FFFFFF;text-decoration:none;}a:active{color:#FFFFFF;text-decoration:none;}
-->
</style>
</head>
<body>
<div class="main">
<?php
if(isset($message)){
?>
<span style="font-size: 100px">:)</span>
<br><li><b><?php echo $message;} else{ ?></b>
<span style="font-size: 100px">:(</span>
<br><li><b><?php echo $error;} ?></b>
</present>
<li>页面自动跳转 等待时间：<b id="wait"><?php echo $waitSecond; ?></b></li>
<td height="33" colspan="6" class="font14"><b>您可以：</b></td>
<td width="86"><a id="href" href="<?php echo $jumpUrl; ?>">点击手动跳转</a></td>
<span style="font-size: 13px; color: #fff;"></br>
Copyright &copy; 2013 - <script>var dd=new Date();var year=dd.getFullYear();if(year)document.write(year);</script>
</span></div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>