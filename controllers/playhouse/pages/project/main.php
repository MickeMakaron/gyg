<?php
/*
 * Page's main file.
 *
 * Control is redirected here when the controller
 * receives this page's ID as second argument.
 */
include(__DIR__ . '/config.php');
 
$args = $renderData['args'];
$argCount = count($args);

$renderData['style'] = "@import url('/file/playhouse/pages/project/style/project.css');";

if($argCount > 0)
{
	$arg = $args[0];
	switch($arg)
	{
		case 'edit':
			include('edit.php');
			break;
		case 'delete':
			include('delete.php');
			break;
		case 'create':
			include('create.php');
			break;
		default:
			include('view.php');
	}
}
else
{
	include('viewAll.php');
}	
