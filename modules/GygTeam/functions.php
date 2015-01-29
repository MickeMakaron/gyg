<?php

// Enclose in user namespace.
class GygTeam
{
	private $teamTable;
	private $teamRankTable;
	private $teamMemberTable;

	public function __construct($db, $tableName)
	{
		include_once(__DIR__ . '/team.php');
		include_once(__DIR__ . '/teamRank.php');
		include_once(__DIR__ . '/teamMember.php');
	
		$this->teamTable = new GygTeamTable($db, $tableName);
		$this->teamRankTable = new GygTeamRankTable($db, $tableName . '_rank');
		$this->teamMemberTable = new GygTeamMemberTable($db, $tableName . '_member');
		
		$this->teamTable->init();
		$this->teamRankTable->init();
		$this->teamMemberTable->init();
	}
};