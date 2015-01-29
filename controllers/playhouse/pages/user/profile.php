<?php

if($argCount > 0)
{
	$userKey = $args[0];
	$user = $db->select('user', ['key' => $userKey]);
	if(count($user) === 0)
		httpStatus::send(404, "User '{$userKey}' not found");
		
	$user = $user[0];
}
else
{
	$user = $userSession->get();
	$userKey = $user['key'];
}

$posts = tableMap::join('post', 'user', $user['id']);
$projects = tableMap::join('project', 'user', $user['id']);

if(!$userSession->isLoggedIn() || $userKey !== $userSession->get()['key'])
{
	for($i = 0; $i < count($posts); $i++)
		if(!$posts[$i]['published'])
			unset($posts[$i]);

	for($i = 0; $i < count($projects); $i++)
		if(!$projects[$i]['published'])
			unset($projects[$i]);
}

ob_start();	
include(__DIR__ . '/templates/profile.tpl.php');
$renderData['content'] .= ob_get_clean();

if($userSession->hasClearance('admin'))
	include('admin.php');
	
// Meta
$renderData['title'] = "{$user['name']}, user profile";
$renderData['description'] = "User profile. View posts and projects by user and general info.";
$renderData['keywords'] = array_merge($renderData['keywords'], ['user profile', 'user', 'profile', $user['name'], $user['key']]);