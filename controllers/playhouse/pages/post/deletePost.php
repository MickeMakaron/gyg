<?php
if(!isset($args[1]))
	httpStatus::send('404');
	
$key = $args[1];
if($userSession->hasClearance('admin'))
	$db->executeQuery('DELETE FROM Post WHERE key=?', [$key]);

header("Location: ". "/playhouse");
exit();