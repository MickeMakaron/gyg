<?php

/*
 * This content will always be shown.
 * The header and menu will never be changed.
 * The title is just a default value, so feel
 * free to change it in a specific page.
 */
 
// Meta data
$renderData['description'] = 'Personal programming blog and portfolio by Mikael Hernvall';
$renderData['keywords'] =
[
	'mikael',
	'hernvall',
	'micke',
	'makaron',
	'Micke Makaron',
	'MickeMakaron',
	'Vendrii',
	'programming',
	'javascript',
	'php',
	'css',
	'c++',
	'blog',
	'portfolio',
	'playhouse',
];
 
// Base url
$renderData['baseUrl'] = $gyg->getBaseUrl();
 
// Title of page
$renderData['baseTitle'] = "Playhouse - Programming about";

// Page's favicon
$renderData['favicon'] = $gyg->path2url(__DIR__ . "/../img/faviconMikael.png");


// Scripts
$renderData['scripts'] = 
[
	COMMON_REQPATH . 'js/jquery.js',
	COMMON_REQPATH . "js/splash.js",
	COMMON_REQPATH . 'js/login.js',
];


// Main template.
$renderData['templatePath'] = __DIR__ . "/templates/main.tpl.php";

////////////////////////////////
// Above
$renderData['above'] = include('above.php');
////////////////////////////////

////////////////////////////////
// Header and splashtext.
// Get a splash silently from the ajax document.
$silent = true;
$renderData['splash'] = include('ajax/splash.php');

$renderData['home'] = $gyg->getBaseUrl() . "/playhouse";
$renderData['headerImage'] = $gyg->path2url(__DIR__ . "/../img/headerCircleMikaelMedium.png");
$renderData['header'] = "<h1>Playhouse</h1><h2>- Programming about</h2>";
////////////////////////////////


////////////////////////////////
// Menu
$renderData['menu'] = include("menu.php");
////////////////////////////////


////////////////////////////////
// Footer
$renderData['copyright'] = "&copy; 2014-2015 <a href='/playhouse/user/mikael-hernvall'>Mikael Hernvall</a>";
$renderData['poweredBy'] = "Powered by: <a href='https://github.com/MickeMakaron/gyg-framework'><img src='".$gyg->path2url(__DIR__ . '/../../gyg/img/gyg.png')."'> gyg-framework</a>";
////////////////////////////////


// Stylesheet
$renderData['stylesheet'] = $gyg->path2url(__DIR__ . "/../style/style.css");


////////////////////////////////
// Set remaining variables to null to prevent PHP error.
$renderData['content'] = null;
$renderData['contentNav'] = null;
////////////////////////////////
