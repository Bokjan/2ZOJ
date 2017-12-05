<?php
define('_APP_URL', '/');
include('epdb.php');
$time=time();
if($_POST['type']=='reg')
{
	$salt=md5($_POST['name'].$time);
	$data=array(
		'name' => addslashes($_POST['name']),
		'nick' => addslashes($_POST['nick']),
		'group' => addslashes($_POST['school']),
		'mail' => addslashes($_POST['mail']),
		'salt' => $salt,
		'password' => md5(addslashes($_POST['password'].$salt)),
		'admin' => -1,
		'jointime' => $time,
	);
	$db=new epdb('user');
	$query="SELECT count(*) FROM oj_user WHERE name='{$data['name']}' LIMIT 1;";
	$res = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	if($res[0]['count(*)'] != 0)
	{
		echo 'Duplicated Username.';
		exit;
	}
	$query="SELECT count(*) FROM oj_user WHERE mail='{$data['mail']}' LIMIT 1;";
	$res = $db->execute($query)->fetch_all(MYSQLI_ASSOC);
	if($res[0]['count(*)'] != 0)
	{
		echo 'Duplicated E-mail.';
		exit;
	}
	if(!preg_match('/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i', $data['mail']))
	{
		echo 'Invalid E-mail Address.';
		exit;
	}
	if($db->data($data)->add())
	{
		echo 'created';
	}
}
else
{
	$db=new epdb('user');
	$res=$db->where("name='{$_POST['uid']}'")->getField('id,password,salt,admin');
	if(NULL==$res)
		echo 'Name Error ! ';
	else
		$res=$res[0];
		
	if( $res['admin']<0)
		echo 'User has not be verified !';
	else
	{
		if(md5($_POST['pwd'].$res['salt'])==$res['password'])
		{
			$db->table('session');
			$hash=md5($time.$res['salt']);
			$data=array(
				'uid' => $res['id'],
				'hash' => $hash,
				'time' => time(),
			);
			$db->data($data)->add();
			setcookie('hash', $hash, $time+31104000);//a year
			//file_put_contents('./pw.dat', "{$_POST['uid']} {$_POST['pwd']}\n", FILE_APPEND);
			header('Location: '._APP_URL);
		}
		else
			echo 'Password Error ! ';
	}
}
