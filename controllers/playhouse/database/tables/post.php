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
 * This file will be included by $this->gygDb->callTableFunction in the database functions file.
 * Make sure to enable the function name in the db config file when using it.
 *
 * The items in the array does not have to be functions. If an item is not a function,
 * that items value will be returned by $this->gygDb->callTableFunction. So it's possible to do this:
 * return
 * [
 * 'init' => 'Hello!'
 *	];
 * When calling $this->gygDb->callTableFunction($tableId, 'init'), 'Hello!' will be returned.
 *
 * If an item is a function, it will be called by $this->gygDb->callTableFunction, and the return value 
 * of the function will subsequently be returned by $this->gygDb->callTableFunction.
 */

class PostTable extends GygDatabaseTable
{

	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}
	
	public function init()
	{
		if($this->gygDb->tableExists('post'))
			return;

		$columns = 
		[
			"id			INTEGER PRIMARY KEY",
			"key		TEXT KEY",
			"title		TEXT", 
			"data		TEXT", 
			"filter		TEXT", 
			"published	DATETIME default NULL",
			"created	DATETIME default (datetime('now'))", 
			"updated	DATETIME default NULL", 
			"deleted	DATETIME default NULL", 
		];
		
		$this->gygDb->create('post', $columns);
		

		tableMap::create('user', 'post');
		tableMap::create('team', 'post');
		tableMap::create('project', 'post');
		tableTag::create('post');
		
		return "Initialized post table";
	}
	
	public function insert($args)
	{
		$requiredArgs =
		[
			'key',
			'title',
			'data',
			'filter',
			'project',
		];
		
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]))
				httpStatus::send(405);

		$userSession = new GygUserSession('user');
		if(!$userSession->hasClearance('admin'))
			httpStatus::send(403, 'User does not have permission.');

		$key = parse::toUrlKey($_POST['title']);
		if(!$key)
			GygAjax::send(false, "Post title must contain numbers and/or letters.");

		$key = parse::toUniqueTableValue('post', 'key', $key, $this->gygDb);

		$params = 
		[
			'key' => $key,
			'title' => htmlentities($_POST['title']),
			'data' => htmlentities($_POST['data']),
			'filter' => $_POST['filter'],
			'published' => isset($_POST['published']) ? date('Y-m-d H:i:s') : null,
		];

		$post = $this->gygDb->insert('post', $params);
		$postId = $this->gygDb->lastInsertId();
		
		$projectId = $_POST['project'];
		
		tableMap::init($this->gygDb);
		tableMap::update('post', 'project', ['project_id' => $projectId], ['post_id' => $postId]);

		
		// Bind post to user author.
		$this->gygDb->insert(tableMap::get('user', 'post'), ['post_id' => $postId, 'user_id' => $_POST['userId']]);
		
		// Tag the post.
		tableTag::init($this->gygDb);
		tableTag::update('post', $postId, explode(' ', $_POST['tags']));

		GygAjax::send(true, "/playhouse/post/{$params['key']}");
	}
	
	public function update($args)
	{
		$requiredArgs =
		[
			'key',
			'title',
			'data',
			'filter',
			'id',
			'project',
		];
		
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]))
				httpStatus::send(405);

		$postId = $_POST['id'];
		tableMap::init($this->gygDb);
		$userSession = new GygUserSession('user');
		if(!$this->gygDb->rowExists(tableMap::get('user', 'post'), ['user_id' => $userSession->get()['id'], 'post_id' => $postId]))
			GygAjax::send(false, 'Post does not belong to user.');


		$oldKey = $_POST['key'];
		$key = parse::toUrlKey($_POST['title']);
		
		if(!$key)
			GygAjax::send(false, "Post title must contain numbers and/or letters.");
		
		
		// If the old key is the same as the new one, don't change key.
		if($key === $oldKey)
			$key = $oldKey;
		// Else create a new, unique key from the post's title. 
		else
			$key = parse::toUniqueTableValue('post', 'key', $key, $this->gygDb);
		
		
		// If project exists by given project ID, update post-project binding.
		if($this->gygDb->rowExists('project', ['id' => $_POST['project']]))
			tableMap::update('post', 'project', ['project_id' => $_POST['project']], ['post_id' => $postId]);
			
		$params = 
		[
			'key' => $key,
			'title' => htmlentities($_POST['title']),
			'data' => htmlentities($_POST['data']),
			'filter' => htmlentities($_POST['filter']),
			'updated' => date('Y-m-d H:i:s'),
		];
		
		if(isset($_POST['published']))
			$params['published'] = date('Y-m-d H:i:s');
			
		$this->gygDb->update('post', $params, ['key' => $oldKey]);
		
		// Tag the post.
		tableTag::init($this->gygDb);
		tableTag::update('post', $postId, explode(' ', $_POST['tags']));


		GygAjax::send(true, "/playhouse/post/{$params['key']}");
	}
	
	public function clear()
	{
		if(!$userSession->hasClearance('admin'))
			httpStatus::send(403, 'User does not have permission');

		$this->gygDb->clear('post');
	}
	
	public function getPublished()
	{
		$res = $this->gygDb->selectAndFetchAll("SELECT * FROM post WHERE published IS NOT NULL ORDER BY created DESC");
		return $res;
	}
};