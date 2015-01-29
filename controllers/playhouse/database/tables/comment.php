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
 * This file will be included by $gygDb->callTableFunction in the database functions file.
 * Make sure to enable the function name in the db config file when using it.
 *
 * The items in the array does not have to be functions. If an item is not a function,
 * that items value will be returned by $gygDb->callTableFunction. So it's possible to do this:
 * return
 * [
 * 'init' => 'Hello!'
 *	];
 * When calling $gygDb->callTableFunction($tableId, 'init'), 'Hello!' will be returned.
 *
 * If an item is a function, it will be called by $gygDb->callTableFunction, and the return value 
 * of the function will subsequently be returned by $gygDb->callTableFunction.
 */

class CommentTable extends GygDatabaseTable
{

	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}

	/*
	* Init the table and create appropriate tables.
	*/
	public function init()
	{
		if($this->gygDb->tableExists('comment'))
			return;

		$columns = 
		[
			"id			INTEGER PRIMARY KEY",
			"data		TEXT NOT NULL", 
			"filter		TEXT", 
			"username 	TEXT NOT NULL",
			"post_id	INTEGER",
			"published	DATETIME default NULL",
			"created	DATETIME default (datetime('now'))", 
			"updated	DATETIME default NULL", 
			"deleted	DATETIME default NULL", 
			"FOREIGN KEY(post_id) REFERENCES post(id) ON DELETE SET NULL",
		];

		$this->gygDb->create('comment', $columns);
		
		tableMap::create('user', 'comment');
		tableMap::create('post', 'comment');
		
		return "Initialized comment table";
	}

	public function insert($args)
	{
		$requiredArgs =
		[
			'data',
			'username',
			'userId',
			'filter',
			'postId',
		];
		
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]))
				httpStatus::send(405);

		if(!$_POST['data'] || !$_POST['username'])
			GygAjax::send(false, 'Please fill out the forms before submitting.');

		$params = 
		[
			'data' => parse::bbcode2html($_POST['data']),
			'username' => htmlentities($_POST['username']),
			'filter' => $_POST['filter'],
			'post_id' => $_POST['postId'],
			'published' => isset($_POST['published']) ? date('Y-m-d H:i:s') : null,
		];

		$this->gygDb->insert('comment', $params);
		$commentId = $this->gygDb->lastInsertId();

		// If author is not anonymous, insert binder.
		if($_POST['userId'])
		{
			tableMap::init($this->gygDb);
			$this->gygDb->insert(tableMap::get('user', 'comment'), ['comment_id' => $commentId, 'user_id' => $_POST['userId']]);
		}
		GygAjax::send(true);
	}
};
