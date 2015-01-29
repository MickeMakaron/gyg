<?php
$projects = $projectTable->getPublished();

if(count($projects) === 0)
{
	$renderData['content'] = '<p>There are no projects :(</p>';
	return;
}

ob_start();
foreach($projects as $project)
{
	include(__DIR__ . '/getProject.php');
	$data = parse::bbcode2html($data);
	include(__DIR__ . '/templates/projectPreview.tpl.php');
}
 
$renderData['content'] = ob_get_clean();


// Meta
$renderData['title'] = "Latest projects";
$renderData['description'] = "View the latest programming projects.";
$renderData['keywords'] = array_merge($renderData['keywords'], ['projects', 'project', 'hobby']);
