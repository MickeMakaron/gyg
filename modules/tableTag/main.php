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
 
class tableTag
{
	static private $db = null;
	
	static function init($db)
	{
		self::$db = $db;
	}

	static function create($masterTable, $primaryKeyId = 'id', $primaryKeyType = 'INTEGER')
	{
		// If master table does not exist, return false.
		if(!self::$db->tableExists($masterTable))
			return false;
			
		/*
		 * The name of the tag table is the master table's
		 * name with a '_tag' suffix.
		 * If a table of that name already exists, return false.
		 */
		$tagTable = $masterTable . '_tag';
		if(self::$db->TableExists($tagTable))
			return false;
		
		self::$db->create($tagTable,
		[
			"id			INTEGER PRIMARY KEY",
			"key		KEY",
			"reference  {$primaryKeyType} KEY",
			"FOREIGN KEY(reference) REFERENCES {$masterTable}({$primaryKeyId}) ON DELETE CASCADE",
		]);
		
		return true;
	}
	
	static public function update($masterTable, $referenceId, $keys)
	{
		// Get current tags.
		$currentTags = self::getTags($masterTable, $referenceId);

		// Remove old tags.
		$oldTags = array_diff($currentTags, $keys);
		foreach($oldTags as $tag)
			self::delete($masterTable, $referenceId, $tag);		

		// Insert new tags.
		$newTags = array_diff($keys, $currentTags);
		foreach($newTags as $tag)
			self::insert($masterTable, $referenceId, $tag);
	}

	static function delete($masterTable, $referenceId, $tag)
	{
		self::$db->delete($masterTable . '_tag', ['reference' => $referenceId, 'key' => $tag]);
	}
	
	static function insert($masterTable, $referenceId, $tag)
	{	
		self::$db->insert($masterTable . '_tag', ['reference' => $referenceId, 'key' => $tag]);
	}
	
	static function getTags($masterTable, $id)
	{
		$tagTable = $masterTable . '_tag';
		if(!self::$db->tableExists($tagTable))
			return [];
		
		$res = self::$db->select($tagTable, ['reference' => $id]);
		
		$tags = [];
		foreach($res as $row)
			array_push($tags, $row['key']);
			
		
		return $tags;
	}
	
	static function getMasters($masterTable, $idCol, $tagKey, $orderBy = null, $orderDirection = 'DESC')
	{
		// If master table does not exist, return false.
		if(!self::$db->tableExists($masterTable))
			return false;
			
		/*
		 * If tag table does not exist, return false.
		 */
		$tagTable = $masterTable . '_tag';
		if(!self::$db->TableExists($tagTable))
			return false;

		$order = null;
		if($orderBy)
			$order = "ORDER BY {$orderBy} {$orderDirection}";
		
		$sql = 
		"
			SELECT {$masterTable}.*
			FROM {$masterTable}
			JOIN {$tagTable}
			ON {$masterTable}.{$idCol} = {$tagTable}.reference
			WHERE {$tagTable}.key = ?
			{$order}
		";
		

		$res = self::$db->selectAndFetchAll($sql, [$tagKey]);
		
		return $res;
	}
};