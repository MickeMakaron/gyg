<?php

class GygTeamRankTable extends GygDatabaseTable
{

	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}

	/*
	 * Initialize table.
	 */
	public function init()
	{
		if($this->gygDb->tableExists('team_rank'))
			return;
	
		$properties = 
		[
			"id 		INTEGER PRIMARY KEY", 
			"name 		TEXT",
			"team_id 	INTEGER KEY",
			"level 		INTEGER",
			"FOREIGN KEY(team_id) REFERENCES team(id) ON DELETE CASCADE"
		];

		// Make it.
		$this->gygDb->create('team_rank', $properties);
	}
	
	/*
	 * Create a rank by inserting it into the rank database.
	 *
	 * If rank already exists, return false.
	 */
	public function insert($args)
	{
		
		// Set rank properties.
		$params =
		[
			'name' 		=> $name,
			'team_id'	=> $teamId,
			'level'		=> $level,
		];
		
		// Insert into database.
		$this->gygDb->insert('team_rank', $params);
		return true;
	}
};