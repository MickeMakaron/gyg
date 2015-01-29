<?php
if(isset($_GET['login']))
{
	$requiredArgs = ['name', 'password'];
	
	foreach($requiredArgs as $arg)
		if(!isset($_POST[$arg]))
			httpStatus::send(405, 'Invalid arguments');
			

	$userTable = new GygUserTable($db, 'user');
	
	$userData = $userTable->validateLogin($_POST['name'], $_POST['password']);
	

	if($userData !== null)
	{
		$userSession = new GygUserSession('user');
		$userSession->login($userData);
		
		GygAjax::send(true);
	}
	else
		GygAjax::send(false, 'Invalid username or password.');

}
else if(isset($_GET['logout']))
{
	$userSession = new GygUserSession('user');
	$userSession->logout();
}
else if(isset($_GET['loginForm']))
{
	include('templates/loginForm.tpl.php');
	exit();
}
else if(isset($_GET['drop']))
	include('drop.php');
else if(isset($_GET['edit']))
{
	$requiredArgs = ['about', 'id'];
	
	foreach($requiredArgs as $arg)
		if(!isset($_POST[$arg]))
			httpStatus::send(405, 'Invalid arguments');
			

	$userTable = new GygUserTable($db, 'user');
	$userData = $userTable->update(['about' => parse::bbcode2html($_POST['about'])], ['id' => $_POST['id']]);
}