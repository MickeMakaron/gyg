<?php

class GygTeamMemberTable extends GygDatabaseTable
{
	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}

	public function init()
	{
		if($this->gygDb->tableExists('team_member'))
			return;
	
		$columns = 
		[
			"id 		INTEGER PRIMARY KEY", 
			"joined 	DATETIME default (datetime('now'))",
			"rank 		INTEGER",
			"team_id 	INTEGER KEY",
			"user_id 	INTEGER KEY",
			"FOREIGN KEY(team_id) REFERENCES team(id) ON DELETE CASCADE",
			"FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE",
		];
			
		// Make it.
		$this->gygDb->create('team_member', $columns);
	}
	
	/*
	 * Create a member by inserting it into the member database.
	 *
	 * If member already exists, return false.
	 */
	public function insert($args)
	{
		if(!isset($args['userId']) && !isset($_POST['userId']))
			return false;
		$userId = isset($args['userId']) ? $args['userId'] : $_POST['userId'];
		
		if(!isset($args['teamId']) && !isset($_POST['teamId']))
			return false;
		$teamId = isset($args['teamId']) ? $args['teamId'] : $_POST['teamId'];
	
		if(team::hasMember($teamId, $userId))
			return false;
		
		// Set member properties.
		$params =
		[
			'rank'		=> $rank,
			'team_id'	=> $teamId,
			'user_id'	=> $userId,
		];
		
		// Insert into database.
		$this->gygDb->insert('team_member', $params);
		return true;
	}
};