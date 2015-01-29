<?php

$db = new GygDatabase($dsn);

return 
[
	'splash' => __DIR__ . '/common/ajax/splash.php',
	'post' => __DIR__ . '/pages/post/ajax.php',
	'user' => __DIR__ . '/pages/user/ajax.php',
	'project' => __DIR__ . '/pages/project/ajax.php',
];