<?php

$keys = array_keys($_GET);
if(!isset($keys[0]))
	httpStatus::send(405, 'Invalid arguments');
	
	
include_once(__DIR__ . '/../../database/tables/post.php');
$postTable = new PostTable($db, 'post');


switch($keys[0])
{
	case 'insert':
		$postTable->insert([]);
		break;
	case 'update':
		$postTable->update([]);
		break;
	case 'delete':
		$postTable->delete();
		break;
	case 'comment':
		include_once(__DIR__ . '/../../database/tables/comment.php');
		$commentTable = new CommentTable($db, 'comment');
		$commentTable->insert([]);
		break;
	default:
		httpStatus::send(405, 'Invalid arguments');
}