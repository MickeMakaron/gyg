<?php


$tables = $db->selectAndFetchAll("SELECT name FROM sqlite_master WHERE type=?", ['table']);

array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/tables.js'));
ob_start();
include(__DIR__ . '/templates/admin.tpl.php');
$renderData['content'] .= ob_get_clean();
