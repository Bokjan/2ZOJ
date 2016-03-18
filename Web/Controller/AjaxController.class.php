<?php
class AjaxController extends Controller
{
	public function MarkProblem()
	{
		global $user;
		$operation = $_GET['op'];
		$pid = $_GET['pid'];
		if(($operation != 'add' && $operation != 'remove') && !is_numeric($pid))
		{
			die("Invalid!");
		}
		$db = new epdb('marked');
		if($operation == 'add')
		{
			$db->execute("INSERT INTO oj_marked SET uid = {$user->id}, pid = {$pid};");
		}
		else
		{
			$db->execute("DELETE FROM oj_marked WHERE uid = {$user->id} AND pid = {$pid};");
		}
		echo '__ok__';
	}
	public function EditProfile()
	{
		global $user;
		$db = new epdb('user');
		if($_POST['type'] != 'profile')
			die();
		if(strlen($_POST['nick']) > 80)
			die('Nickname is too long!');
		if(md5($_POST['oldpwd'].$user->salt) != $user->password)
			die('Old Password isn\'t Correct!');
		if(!preg_match('/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i', $_POST['email']))
			die('Illegal E-Mail Address!');
		$newpwd;
		if($_POST['newpwd']=='')
			$newpwd = $user->password;
		else
			$newpwd = md5($_POST['newpwd'].$user->salt);
		$query = "
		UPDATE oj_user
		SET nick = '{$_POST['nick']}',
		 password = '{$newpwd}',
		 mail = '{$_POST['email']}'
		WHERE
			id = {$user->id}
		LIMIT 1;
		";
		$db->execute($query);
		echo 'User Info Updated.';
	}
}