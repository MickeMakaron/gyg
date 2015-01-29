<?php
if(!$userSession->hasClearance('admin'))
	httpStatus::send(404);

if(!isset($args[1]))
	httpStatus::send('404');
	


$key = $args[1];
if($userSession->hasClearance('admin'))
	$db->executeQuery('DELETE FROM Project WHERE key=?', [$key]);

header("Location: ". "/playhouse/project");
exit();