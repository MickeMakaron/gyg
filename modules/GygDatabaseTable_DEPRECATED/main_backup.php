<?php

/*
 * Basic database functions for inserting and fetching data.
 */
class GygDatabaseTable
{
	protected $db;
	protected $tableName;
	
	public function __construct($db, $tableName)
	{
		 $this->db = $db;
		 $this->tableName = $tableName;
		 /*
		$this->db->executeQuery(
		"
			CREATE TABLE IF NOT EXISTS table_settings
			(
				id 				INTEGER PRIMARY KEY,
				table_name 		TEXT NOT NULL,
				enabled 		BOOLEAN NOT NULL DEFAULT 1,
				functions_path 	TEXT DEFAULT NULL
			)
		");

		$this->db->executeQuery(
		"
			CREATE TABLE IF NOT EXISTS table_function
			(
				id 				INTEGER PRIMARY KEY,
				class_name		TEXT,
				function_name	TEXT NOT NULL,
				enabled			BOOLEAN NOT NULL DEFAULT 1,
				table_id 		INTEGER	KEY NOT NULL,
				FOREIGN KEY(table_id) REFERENCES table_settings(id) ON DELETE CASCADE
			)
		");
		*/
	}
	
	
/*
	 * \brief Assign functions to table
	 *
	 * The functions array must be structured
	 * in a strict manner:
	 *	$functions = 
	 *	[
	 *		'functionName1' => bool,
	 *		'functionName2' => bool,
	 *		...		
	 *	];
	 * where key is the function name and value
	 * is a boolean designating whether to make
	 * function callable (true) or not (false).
	 *
	 * \param table string, ID of table.
	 * \param pathToFunctions string, absolute path to functions file.
	 * \param functions array, functions to assign to table. See description for formatting.
	 */
	 /*
	public function assignTableFunctions($pathToFunctions, $functions, $className = '')
	{
		$res = $this->db->select('table_settings', ['table_name' => $this->tableName]);
		if(count($res) === 0)
			throw new Exception("GygDatabase::assignTableFunctions (functions.php): No '{$this->tableName}' table found.");
		$tableId = $res[0]['id'];
			
		if(!is_file($pathToFunctions))
			throw new Exception("GygDatabase::assignTableFunctions (functions.pph): File '{$pathToFunctions}' not found.");
			
		$this->db->update('table_settings', ['functions_path' => $pathToFunctions], ['table_name' => $this->tableName]);
			
		if(!is_array($functions))
			throw new Exception("GygDatabase::assignTableFunctions (functions.php): Invalid parameter 'functions'. Must be array.");


		foreach($functions as $name => $isEnabled)
		{
			echo "Inserted {$this->tableName} function {$name}";
			echo "<br>";
		
			if(!is_string($name))
				throw new Exception("GygDatabase::assignTableFunctions (functions.php): Invalid parameter 'functions'. Key must be string.");
				
			if(!is_bool($isEnabled))
				throw new Exception("GygDatabase::assignTableFunctions (functions.php): Invalid parameter 'functions'. Value must be boolean.");
		
		
			$this->db->insert('table_function',
			[
				'class_name'	=> $className,
				'function_name' => $name,
				'enabled' => $isEnabled,
				'table_id' => $tableId,
			]);
		}
	}
	*/
	

	/*
	 * Check if the table exists in the database's
	 * whitelist and is enabled.
	 */
	 /*
	public function tableIsEnabled($tableId)
	{
		// These two standard tables are always enabled.
		if($tableId === 'table_settings' || 'table_function')
			return true;
	
		// Get table settings.
		$res = $this->db->select('table_settings', ['table_name' => $tableId]);
		
		// If no settings found, table does not exist.
		if(count($res) === 0)
			return false;
			
		return $res['enabled'];
	}
	*/
	/*
	 * Check if a function is set and enabled
	 * in the table whitelist.
	 */
	 /*
	public function tableFunctionIsEnabled($table, $function)
	{	
		// These two standard tables have no functions.
		if($table === 'table_settings' || $table === 'table_function')
			return false;
	
		// Return false if table does not exist.
		if(!$this->db->tableExists($table))
			return false;
		

		
		// Get table settings.
		$tableSettings = $this->db->select('table_settings', ['table_name' => $table])[0];
		
		// Return false if table is disabled.
		if(!$tableSettings['enabled'])
			return false;
			
		// Return false if table function file does not exist.
		if(!is_file($tableSettings['functions_path']))
			return false;
			

			
		// Get function settings of table.
		$functionSettings = $this->db->select('table_function', ['table_id' => $tableSettings['id'], 'function_name' => $function]);
		
		// Return false if function does not exist.
		if(count($functionSettings) === 0)
			return false;
			
		// Return false if function is disabled, else true.
		return $functionSettings[0]['enabled'] == true;
	}
	*/
	
	/*
	 * Call a table function by ajax.
	 */
	 /*
	 public function callTableFunctionByAjax($functionId, $params = [])
	 {	 
		if(!$this->tableFunctionIsEnabled($this->tableName, $functionId))
		{
				header('Content-type: application/json');
				echo json_encode(['success' => false, 'output' => "Table function '{$functionId}' for table '{$this->tableName}' unavailable"]);
				exit();
		}
		
		$tableSettings = $this->db->select('table_settings', ['table_name' => $this->tableName])[0];
		include_once($tableSettings['functions_path']);
	
		// Get function settings.
		$functionSettings = $this->db->select('table_function', ['table_id' => $tableSettings['id'], 'function_name' => $functionId])[0];

		// Make sure class exists and it is extending this class.
		$functionClass = $functionSettings['class_name'];
		if(class_exists($functionClass, false) && get_parent_class($functionClass) === 'GygDatabaseTable')
		{
			$obj = new $functionClass($this->db, $this->tableName);
			
			// If method exists, call it and return its return value.
			$functionName = $functionSettings['function_name'];
			if(method_exists($obj, $functionName))
				return $obj->{$functionName}($params);
		}
		
		// Else, send failure message via json.
		header('Content-type: application/json');
		echo json_encode(['success' => false, 'output' => "Table function '{$functionName}' for table '{$this->tableName}' at '{$tableSettings['functions_path']}' not callable."]);
		exit();
	 }
	*/
	 
	 /**
	  * \brief Check if keys exist in array
	  *
	  * Check if all keys exist in an array.
	  *
	  * \param string keys keys to search for.
	  * \param array search array to search in.
	  *
	  * \returns bool True if all keys exist in the array, else false.
	  */
	  /*
	 public function arrayKeysExist($keys, $search)
	 {
		// Set values of $keys to keys.
		$keys = array_flip($keys);
		
		// Get elements with matching keys.
		$match = array_intersect_key($keys, $search);
	 
		 
		// If the number of matches are the same as the requested keys,
		// return true, else return false. 
		return count($match) === count($keys);
	 }
	 */
};