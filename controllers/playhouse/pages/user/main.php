<?php

/*
 * Page's main file.
 *
 * Control is redirected here when the controller
 * receives this page's ID as second argument.
 */

$args = $renderData['args'];
$argCount = count($args);
// If request contains arguments, interpret it as an ajax call from
// a form submission and include the ajax document.

include(__DIR__ . "/content.php");


