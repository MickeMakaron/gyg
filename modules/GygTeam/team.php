<?php

class GygTeamTable extends GygDatabaseTable
{
	private $teamTable;
	private $teamRankTable;
	private $teamMemberTable;

	
	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}
	
	/**
	 * Initialize User table and create default admin.
	 */
	public function init()
	{
		if($this->gygDb->tableExists('team'))
			return;
	
		$columns = 
		[
			"id INTEGER PRIMARY KEY", 
			"name TEXT",
			"key TEXT KEY",
			"description TEXT",
			"created DATETIME default (datetime('now'))",
			"updated DATETIME default NULL",
		];
			
		// Make it.
		$this->gygDb->create('team', $columns);
		tableTag::create('Team');
	}

	public function insert($args)
	{
		if(!isset($args['name']) && !isset($_POST['name']))
			return false;
		$name = isset($args['name']) ? $args['name'] : $_POST['name'];
	
		// Parse $name into an url friendly key.
		$key = parse::toUrlKey($name);
	
		// Return false if a team or team of that key already exists.
		if(count($this->gygDb->select('team', ['key' => $key])) > 0)
			return false;
		
		// Set team properties.
		$params =
		[
			'name' 		=> $name,
			'key' 			=> $key,
			'description'	=> $description,
		];
		
		// Insert into database.
		$this->gygDb->insert('Team', $params);
		return true;
	}
};