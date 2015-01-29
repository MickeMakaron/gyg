<?php
/****************************************************************
****************************************************************
*
* gyg-file-controller - File request handler using gyg-framework.
* Copyright (C) 2014-2015 Mikael Hernvall (mikael.hernvall@gmail.com)
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*
****************************************************************
****************************************************************/

/*
 * The File controller creates a good-looking linking structure for files.
 * If you want to link to a file, simply link to it using gyg's createImgUrl function. 
 * The visible link will have the format "fileController/controller/filePath" if in the
 * controller's directory, and "fileController/controller/page/filePath" if
 * in the page's directory. 
 *
 * The page argument is interpreted as the controller containing the file.
 * Furthermore, at least 1 argument is required. The minimum is thus this:
 * 		controller/filePath.
 *
 * If the file is under a specific page, however, it is possible to 
 * create a path relative to the page's directory, instead of the
 * controller's directory:
 *		controller/page/filePath. 
 * thus there is one page argument and two arguments.
 *
 * If the argument list size is 1, it will be interpreted as the
 * file's path relative to the controller's directory.
 *
 * If the argument list size is 2 or greater, the first argument
 * will be interpreted as the page ID, and the remaining arguments
 * will be interpreted as the file's path relative to the page's 
 * directory.
 */


$send404 = function()
{
	header('HTTP/1.0 404 Not Found');
	exit();
};
	
$request = $gyg->getRequest();
$argCount = $request['argCount'];

// We need at least the controller and something else pointing to a file.
if($argCount < 2)
	$send404();


// Deny if controller is not whitelisted.
$controller = $request['args'][0];
if(!$gyg->controllerIsWhitelisted($controller))
	$send404();

// Extract path to file from request.
$filePath = $gyg->getControllersPath() .'/'. implode('/', $request['args']);

// Deny if file does not exist or is not readable.
if(!is_readable($filePath))
	$send404();

// Get extensions whitelist. Deny if file's extension is not whitelisted.
$extensionsWhitelist = include("extensionsWhitelist.php");
if(!in_array(pathinfo($filePath)['extension'], $extensionsWhitelist))
	$send404();


// All is well. Read file.
$imgInfo = getimagesize($filePath);
$mime = $imgInfo['mime'];
header('Content-type: ' . $mime);  
readfile($filePath);
exit();