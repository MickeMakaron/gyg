<?php
/*
 * This file contains table functions for a specific table.
 * To define functions as init and get, simply return an array
 * with function names as keys and your desired function as the value.
 * Example:
 * 
 * return
 * [
 * 'init' => function()
 *	{
 *		//Perform initialization.
 *		//Maybe even return a value?
 *	}
 *	];
 *
 * This file will be included by self::$db->callTableFunction in the database functions file.
 * Make sure to enable the function name in the db config file when using it.
 *
 * The items in the array does not have to be functions. If an item is not a function,
 * that items value will be returned by self::$db->callTableFunction. So it's possible to do this:
 * return
 * [
 * 'init' => 'Hello!'
 *	];
 * When calling self::$db->callTableFunction($tableId, 'init'), 'Hello!' will be returned.
 *
 * If an item is a function, it will be called by self::$db->callTableFunction, and the return value 
 * of the function will subsequently be returned by self::$db->callTableFunction.
 */
 
// Create class
class tableMap
{
	static private $db = null;
	
	static public function init($db)
	{
		self::$db = $db;
	}

	static private function generateTableName($table1, $table2)
	{
		return "{$table1}_{$table2}_map";
	}


	static function create($table1, $table2, $additionalColumns = [], $idCol1 = 'id', $idCol2 = 'id')
	{
		// Throw exception if any of the tables doesn't exist.
		//if(!self::$db->tableExists($table1) || !self::$db->tableExists($table2))
		//	throw new Exception("tableMap::create (main.php): Table does not exist. Table 1: '{$table1}'. Table 2: '{$table2}'");


		// If tables are already bound by a binder table, return false.
		if(self::exists($table1, $table2))
			return false;
	
		// Format column name according to naming convention.
		$foreignIdCol1 = lcfirst($table1 . '_id');
		$foreignIdCol2 = lcfirst($table2 . '_id');
		
		$params = 
		[
			'id INTEGER PRIMARY KEY',
			"{$foreignIdCol1} INTEGER KEY NOT NULL",
			"{$foreignIdCol2} INTEGER KEY NOT NULL",
			"FOREIGN KEY({$foreignIdCol1}) REFERENCES {$table1}({$idCol1}) ON DELETE CASCADE",
			"FOREIGN KEY({$foreignIdCol2}) REFERENCES {$table2}({$idCol2}) ON DELETE CASCADE",
		];
		
		foreach($additionalColumns as $col)
			array_push($params, $col);
	
		$table = self::generateTableName($table1, $table2);
		self::$db->create($table, $params);
	}

	static public function update($table1, $table2, $data, $where)
	{
		$tableMap = self::get($table1, $table2);
		
		if(!self::$db->tableExists($tableMap))
			throw new Exception("tableMap::update (main.php): Table map for '{$table1}' and '{$table2}' does not exist.");
		
		// Abort update if any data is of null value.
		foreach($data as $d)
		{
			if(!$d || $d === 'null')
				return;
		}
		
		if(self::$db->rowExists($tableMap, $where))
			self::$db->update($tableMap, $data, $where);
		else
			self::$db->insert($tableMap, array_merge($data, $where));
	}
	
	
	/**
	 * Get all rows from $master table that are
	 * bound to $slave table by $binder table.
	 *
	 * PARAMS:
	 * master, 				string - ID of table
	 * whose rows are to be returned.
	 * slave, 				string - ID of table
	 * to compare with.
	 * (orderBy), 			string - Column in
	 * master table to order results by.
	 * (orderDirection), 	string - Direction to 
	 * order results by. 'ASC' implies ascending order
	 * and 'DESC' implies descending order.
	 *
	 * RETURNS:
	 * array - All rows of master table that are bound
	 * to rows of slave table.
	 */
	static function join($master, $slave, $slaveRowId = null, $orderBy = null, $orderDirection = 'DESC')
	{
		if(!self::exists($master, $slave))
			throw new Exception("tableMap::join (main.php): Could not find binder table of '{$master}' and '{$slave}'.");
			
		$binder = self::$db->tableExists(self::generateTableName($master, $slave)) ? self::generateTableName($master, $slave) : self::generateTableName($slave, $master);
	

		$masterIdCol = lcfirst($master . '_id');
		$slaveIdCol = lcfirst($slave . '_id');

		$where = null;
		$params = [];
		if($slaveRowId !== null)
		{
			$where = "{$binder}.{$slaveIdCol} = ?";
			$params = [$slaveRowId];
		}
		
		$order = ($orderBy) ? "ORDER BY {$orderBy} {$orderDirection}" : null;
	
		$sql = 
		"
			SELECT {$master}.*
			FROM {$master}
			JOIN {$binder}
			ON {$master}.id = {$binder}.{$masterIdCol}
			WHERE {$where}
			{$order}
		";

		$res = self::$db->selectAndFetchAll($sql, $params);
		
		return $res;
	}
	
	static function exists($table1, $table2)
	{
		$alt1 = self::generateTableName($table1, $table2);
		$alt2 = self::generateTableName($table2, $table1);
		return self::$db->tableExists($alt1) || self::$db->tableExists($alt2);
	}
	
	static function get($table1, $table2)
	{
		$alt1 = self::generateTableName($table1, $table2);
		$alt2 = self::generateTableName($table2, $table1);
	
		if(self::$db->tableExists($alt1))
			return $alt1;
		else if(self::$db->tableExists($alt2))
			return $alt2;
		else
			return false;
	}
};