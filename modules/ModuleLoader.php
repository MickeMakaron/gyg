<?php
/****************************************************************
 ****************************************************************
 * 
 * gyg-modules - Modules framework for web development
 * Copyright (C) 2014 Mikael Hernvall (mikael@hernvall.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 ****************************************************************
 ****************************************************************/


Class ModuleLoader
{
	private $modulesPath;

	public function __construct($modulesPath)
	{
		$this->modulesPath = rtrim(realpath($modulesPath), '/');
		spl_autoload_register([$this, 'loadModule']);
	}

	
	/**
	 * \brief Load module, if whitelisted, by including its main file.
	 *
	 * \param moduleId string ID of module to load.
	 *
	 * \return bool True if module was successfully loaded, else false.
	 */
	public function loadModule($moduleId)
	{
		$modulePath = $this->modulesPath . "/{$moduleId}/main.php";
		
		if(!is_file($modulePath))
			throw new Exception("Main file of '{$moduleId}' module not found. Expected path: '{$modulePath}'.");
		
		require_once($modulePath);
	}

	/**
	 * Load several modules.
	 *
	 * PARAMS:
	 * moduleIds, string array - Array containing IDs
	 * of modules to load.
	 * 
	 * THROWS:
	 * Parameter is not array.
	 */
	 /*
	function loadModules($modules)
	{
		if(!is_array($modules))
			throw new Exception('ModuleLoader::__construct (functions.php): Function parameter must be an array.');
	
		foreach($modules as $id)
		{
			include_once(__DIR__ . "/{$moduleId}/main.php");
			
			$rc = new ReflectionClass($id);
			if(
		}
		
				$rc = new ReflectionClass($className);
			if($rc->implementsInterface('IController')) 
			{
				$formattedMethod = str_replace(array('_', '-'), '', $method);
				if($rc->hasMethod($formattedMethod)) 
				{
					$controllerObj	= $rc->newinstance();
					$methodObj		= $rc->getMethod($formattedMethod);
					
					$methodObj->invokeArgs($controllerObj, $arguments);
	
		foreach($moduleIds as $id)
			$this->loadModule($id);
	}
	*/
};