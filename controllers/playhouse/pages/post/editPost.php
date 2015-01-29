<?php

// Only admins are allowed to edit posts.
if(!$userSession->hasClearance('admin'))
	httpStatus::send('404');
	
// If key is not set.
if(!isset($args[1]))
	httpStatus::send('404');
	

	
$key = $args[1];
$posts = $db->select('post', ['key' => $key]);
if(count($posts) === 0)
	httpStatus::send('404');
	
$post = $posts[0];

include(__DIR__ . '/getPost.php');

$projects = $projectTable->getPublished();


	
$renderData['style'] .= "@import url('/file/playhouse/pages/post/style/form.css');";
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formInputFields.js'));
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formEditButtons.js'));

ob_start();
include(__DIR__ . '/templates/editForm.tpl.php');
$renderData['content'] = ob_get_clean();