<?php

/*
 * Generate HTML for the above
 */

ob_start();
include(__DIR__ . '/templates/above.tpl.php');
$above = ob_get_clean();

return $above;