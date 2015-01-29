<?php
class GygAjaxTable extends GygDatabaseTable
{
	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}

	public function init()
	{
		$this->gygDb->executeQuery(
		"
			CREATE TABLE IF NOT EXISTS {$this->tableName}
			(
				id 				INTEGER PRIMARY KEY,
				file_key		TEXT KEY,
				file_path		TEXT,
				enabled			BOOLEAN NOT NULL DEFAULT 1
			)
		");
	}
	
	public function insert($key, $filePath, $enabled = true)
	{	
		if($this->exists($key))
			return false;
	
		$this->gygDb->insert($this->tableName, 
		[
			'file_key' => $key,
			'file_path' => $filePath,
			'enabled' => $enabled,
		]);
		
		return true;
	}
	
	public function delete($key)
	{
		$this->gygDb->delete($this->tableName, ['file_key' => $key]);
	}
	
	public function enable($key)
	{
		$this->gygDb->update($this->tableName, ['enabled' => true], ['file_key' => $key]);
	}
	
	public function disable($key)
	{
		$this->gygDb->update($this->tableName, ['enabled' => false], ['file_key' => $key]);
	}
	
	public function exists($key)
	{
		return $this->gygDb->rowExists($this->tableName, ['file_key' => $key]);
	}
	
	public function get($key)
	{
		$res = $this->gygDb->select($this->tableName, ['file_key' => $key]);
		
		if(empty($res))
			return null;
		else
			return $res[0];
	}
	
	public function includeFile($key)
	{
		$ajaxFile = $this->get($key);

		if($ajaxFile === null)
			return ['success' => false, 'message' => "Ajax file not found by key '{$key}'"];
		else
		{
			if($ajaxFile['enabled'])
			{
				if(is_readable($ajaxFile['file_path']))
					return ['success' => true, 'message' => "Successfully called ajax file.", 'path' => $ajaxFile['file_path']];
				else
					return ['success' => false, 'message' => "Ajax file at '{$ajaxFile['file_path']}' is not readable or does not exist."];
			}
			else
				return ['success' => false, 'message' => "Ajax file of key '{$key}' is disabled."];
		}
	}
}