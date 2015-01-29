<?php

/*
 * Generate HTML for the menu
 *
 * To use this file. Include it before
 * assigning the menu's HTML and use
 * the $menu variable. Like so:
 *		include("menu.php");
 * 		$renderData['menu'] = $menu;
 *
 * The menu items can be found in config.
 */
 
// The resulting HTML will be stored in $menu
$posts = $postTable->getPublished();
$projects = $projectTable->getPublished();

foreach($projects as &$project)
	$project['created'] = substr($project['created'], 0, 10);

foreach($posts as &$post)
	$post['created'] = substr($post['created'], 0, 10);

	
ob_start();
include('templates/latest.tpl.php');
return ob_get_clean();