<?php

if(!$userSession->hasClearance('admin'))
	httpStatus::send(404);

if(!isset($args[1]))
	httpStatus::send('404');

$key = $args[1];

$res = $db->select('project', ['key' => $key]);

if(count($res) === 0)
	httpStatus::send('404');

	

	
$renderData['title'] = 'Create project | ' . $renderData['title'];	
$renderData['style'] .= "@import url('/file/playhouse/pages/project/style/form.css');";
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formInputFields.js'));
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formEditButtons.js'));
	
	
$project = $res[0];
include('getProject.php');

$codeLinks = $db->select('project_code_link_type');

foreach($codeLinks as &$type)
{
	$res = $db->select('project_code_link', ['project_id' => $project['id'], 'project_code_link_type_id' => $type['id']]);
	
	$codeUrl = count($res) > 0 ? $res[0]['url'] : null;

	$type['codeUrl'] = $codeUrl;
}

ob_start();
include(__DIR__ . '/templates/editForm.tpl.php');

$renderData['content'] = ob_get_clean();