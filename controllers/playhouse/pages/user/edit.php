<?php

if(!$userSession->isLoggedIn())
	httpStatus::send(404, 'User not logged in.');

$user = $db->select('user', ['id' => $userSession->get()['id']]);

if(count($user) === 0)
	httpStatus::send(404, 'User not found');

$user = $user[0];

array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/editForm.js'));
ob_start();
include(__DIR__ . '/templates/aboutForm.tpl.php');
$renderData['content'] .= ob_get_clean();