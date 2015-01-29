<?php

// Initialize modules.
$db = new GygDatabase($dsn);
tableTag::init($db);
tableMap::init($db);
$userSession = new GygUserSession('user');
$userTable = new GygUserTable($db, 'user');

httpStatus::init();




// Init database tables.
$team = new GygTeam($db, 'team');


include_once(__DIR__ . '/database/tables/post.php');
$postTable = new PostTable($db, 'post');

include_once(__DIR__ . '/database/tables/comment.php');
$commentTable = new CommentTable($db, 'comment');

include_once(__DIR__ . '/database/tables/project.php');
$projectTable = new ProjectTable($db, 'project');


// It is recommended that you use a whitelist for your pages
// and use it for your controller.
$gyg->whitelistPages(
[
	'post',
	'project',
	'about',
	'user',	
]);



	
// Always use english.
$renderData['lang'] = 'en';


// File structure in request form.
define('COMMON_REQPATH', $gyg->getBaseUrl() . '/file/playhouse/common/');

// Path to common files.
define('COMMON_PATH', __DIR__ . '/common/');