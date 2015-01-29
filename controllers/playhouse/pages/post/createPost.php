<?php
/*
 * Only admins are allowed to create posts.
 */

if(!$userSession->hasClearance('admin'))
	httpStatus::send('404');


$projects = $projectTable->getPublished();

 
$renderData['title'] = 'Write post | ' . $renderData['title'];
$renderData['style'] .= "@import url('/file/playhouse/pages/post/style/form.css');";
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formInputFields.js'));
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formCreateButtons.js'));

ob_start();
include(__DIR__ . "/templates/createForm.tpl.php");
$renderData['content'] = ob_get_clean();


include(__DIR__ . '/templates/postInfo.tpl.php');
$renderData['contentNav'] = ob_get_clean();