<?php

$page = isset($gyg->getRequest()['args'][0]) ? $gyg->getRequest()['args'][0] : null;

if($page)
{
	if($page === 'randomizeVideo')
		include('ajax/randomizeVideo.php');
	else if($page === 'vids')
	{
		$path = __DIR__ . '/'. implode('/', $gyg->getRequest()['args']);
		$type = mime_content_type($path);
		header("Content-type: {$type}");
		header('Content-Length: '.filesize($path));
		readfile($path);
	}
}
else
	include('nukebox.php');