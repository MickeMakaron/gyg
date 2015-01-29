<?php

/*
 * Basic database functions for inserting and fetching data.
 */
class GygDatabaseTable
{
	protected $gygDb;
	protected $tableName;
	
	public function __construct($gygDb, $tableName)
	{
		 $this->gygDb = $gygDb;
		 $this->tableName = $tableName;
	}
};