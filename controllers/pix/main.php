<?php

$request = $gyg->getRequest();

$argCount = $request['argCount'];
$args = $request['args'];

if($argCount === 0 || $args[0] === 'pix')
	include(__DIR__ . '/about.php');
else
{
	switch($args[0])
	{
		case 'about':
		case 'game':
		case 'download':
			include(__DIR__ . "/{$args[0]}.php");
			break;
			
		default:
			httpStatus::send(404);
	}
}