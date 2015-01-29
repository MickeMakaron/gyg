<?php
/*
 * Show register page by default.
 */
if($argCount === 0)
{
	if($userSession->isLoggedIn())
		include('profile.php');
	else
		include(__DIR__ . '/create.php');
}
else
{
	switch($args[0])
	{
		case 'drop':
			include("drop.php");
			break;
		case 'edit':
			include('edit.php');
			break;
		case 'login':
			include('login.php');
			break;
		default:
			include('profile.php');
			break;
	}
}