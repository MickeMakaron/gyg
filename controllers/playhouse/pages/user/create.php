<?php
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/create.js'));
ob_start();
include(__DIR__ . '/templates/create.tpl.php');
$renderData['content'] = ob_get_clean();