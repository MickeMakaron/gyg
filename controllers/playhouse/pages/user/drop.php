<?php

$userSession = new GygUserSession('user');
if(isset($_POST['table']) && $userSession->hasClearance('admin'))
{
	header('Content-type: application/json');

	$table = $_POST['table'];
	if(!$db->tableExists($table))
	{
		echo json_encode(['output' => "Failed. Table does not exist.", 'table' => $table]);	
		exit();
	}
	
	$db->clear($table);
	$db->drop($table);
	{
		echo json_encode(['output' => "Success!", 'table' => $table]);	
		exit();
	}
}
else
	httpStatus::send(403);