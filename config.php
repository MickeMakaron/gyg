<?php
// PHP ERROR REPORTING
error_reporting(E_ALL);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly	


// TIMEZONE
date_default_timezone_set('UTC');



// GYG-FRAMEWORK
$controllersPath = __DIR__ . '/controllers/';
$baseUrl = "";
$defaultController = 'playhouse';
$useRewriteRule = true;

$controllers = 
[
	'gyg',
	'playhouse',
	'pix',
	'nukebox',
	'file',
	'ajax',
	'database',
];

$shortcuts =
[
	'shortcut' => '',
];

/*
 * Database settings.
 */
$dsn = 'sqlite:' . __DIR__ . '/modules/GygDatabase/database.sqlite';

$modulesPath = __DIR__ . '/modules';

session_start();