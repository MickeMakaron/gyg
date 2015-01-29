<?php

/*
 * Page's main file.
 *
 * Control is redirected here when the controller
 * receives this page's ID as second argument.
 */


$args = $renderData['args'];

$argCount = count($args);

$renderData['style'] = "@import url('/file/playhouse/pages/post/style/post.css');";


if($argCount === 0)
	include(__DIR__ . '/viewAll.php');
else
{
	$arg = $args[0];
	switch($arg)
	{
		case 'edit':
			include(__DIR__ . '/editPost.php');
			break;
		case 'delete':
			include(__DIR__ . '/deletePost.php');
			break;
		case 'create':
			include(__DIR__ . '/createPost.php');
			break;
		case 'tag':
			include(__DIR__ . '/viewByTag.php');
			break;
		default:
			include(__DIR__ . '/viewPost.php');
			break;
	}
}