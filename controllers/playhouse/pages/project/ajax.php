<?php

$keys = array_keys($_GET);
if(!isset($keys[0]))
	httpStatus::send(405, 'Invalid arguments');
	
	
include_once(__DIR__ . '/../../database/tables/project.php');
$table = new ProjectTable($db, 'project');


switch($keys[0])
{
	case 'insert':
		$table->insert([]);
		break;
	case 'update':
		$table->update([]);
		break;
	case 'delete':
		$table->delete();
		break;
		
	default:
		httpStatus::send(405, 'Invalid arguments');
}