<?php
if(!$userSession->hasClearance('admin'))
	httpStatus::send(404);

$renderData['style'] .= "@import url('/file/playhouse/pages/project/style/form.css');";
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formInputFields.js'));
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formCreateButtons.js'));


$codeLinks = $db->select('project_code_link_type');

ob_start();
include(__DIR__ . '/templates/createForm.tpl.php');
$renderData['content'] = ob_get_clean();