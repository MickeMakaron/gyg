<?php


$key = $args[0];

$res = $db->select('project', ['key' => $key]);

if(count($res) === 0)
	httpStatus::send(404);

$project = $res[0];

if(!$project['published'])
{
	if(!$userSession->isLoggedIn())
		httpStatus::send(404);

	if(!$db->rowExists(tableMap::get('project', 'user'), ['user_id' => $userSession->get()['id'], 'project_id' => $project['id']]))
		httpStatus::send(404);
}


include('getProject.php');
$data = parse::bbcode2html($data);

ob_start();
include(__DIR__ . '/templates/project.tpl.php');
$renderData['content'] = ob_get_clean();


// Meta
$renderData['title'] = $project['title'];
$renderData['description'] = $project['description'];
$renderData['keywords'] = array_merge($renderData['keywords'], ['project']);