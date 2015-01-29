<?php
/****************************************************************
****************************************************************
*
* gyg-ajax-controller - Ajax request handling with gyg-framework
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

// GygAjax::send
include_once('GygAjax.php');

// Send failure message and do nothing if there are no arguments.
$request = $gyg->getRequest();
if($request['argCount'] === 0)
	GygAjax::send(false, 'No arguments.');
else
{
	// First argument indicates target controller.
	$controller = $request['args'][0];

	
	// Get path to gygAjax configuration file in controller directory.
	$gygAjaxPath = $gyg->getControllersPath() . "/{$controller}/gygAjax.php";
	
	// Make sure configuration file exists and is readable.
	if(!is_readable($gygAjaxPath))
		GygAjax::send(false, "gygAjax file for controller '{$controller}' is not readable or does not exist. Expected path is '{$gygAjaxPath}'.");
		
	// Include configuration file.
	$ajaxFiles = include($gygAjaxPath);
	
	
	// Extract key from request.
	$key = implode('/', array_slice($request['args'], 1));
	
	// Make sure an ajax file is defined in the configuration file by this key.
	if(!isset($ajaxFiles[$key]))
		GygAjax::send(false, "Ajax file for controller '{$controller}' not found by key '{$key}'");
		
	
	// Get path to ajax file and make sure it exists and is readable.
	$filePath = $ajaxFiles[$key];
	if(!is_readable($filePath))
		GygAjax::send(false, "Ajax file for controller '{$controller}' at '{$ajaxFile['file_path']}' is not readable or does not exist.");
	
	
	// All is well. Include ajax file and send success message.
	include($filePath);
	GygAjax::send(true, "Successfully called ajax file.");
}