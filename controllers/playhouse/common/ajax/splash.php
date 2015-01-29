<?php

/*
 * Generate HTML for the splashtext in the header
 *
 * For a list of all splashtexts, see config file.
 */
include_once(__DIR__ . "/../splashes.php");
 
// Randomly pick a splash from the list of splashes in config
// until a new splash is picked.
$index = null;
$endIndex = count($splashes) - 1;
if(isset($_SESSION['splash']))
{
	do
	{
		$index = rand(0, $endIndex);
	} while($index === $_SESSION['splash']);
}
else
	$index = rand(0, $endIndex);

$splash = $splashes[$index];

$text = null;
$link = null;

// If splash has a link, add it as a tiny exponent.
if(is_array($splash))
{
	if(!isset($splash['text']))
		throw new Exception('Invalid format of splash list in config.');
	
	$text = $splash['text'];
	$link = isset($splash['link']) ? $splash['link'] : null;
}
// Splash does not have a link.
else
{
	$text = $splash;
}

// Format link.
if($link)
	$link = "<a href='{$link}'>1</a>";

$link = "<sup>{$link}</sup>";

$splash = "<h1>{$text}{$link}</h1>";
	
	
// Save splash index in session for future checks.
$_SESSION['splash'] = $index;





// Silently return splash if $silent var is set to true before inclusion.
// Else echo it.
if(isset($_POST['silent']))
{	
	echo "<h1>{$splash}</h1>";
	exit();
}
else
	return $splash;