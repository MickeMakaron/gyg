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
 
 
class ProjectTable extends GygDatabaseTable
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
		if($this->gygDb->tableExists('project'))
			return;

		$columns = 
		[
			"id				INTEGER PRIMARY KEY",
			"key			TEXT KEY",
			"title			TEXT KEY",
			"code_notice	TEXT default NULL",
			"description 	TEXT default NULL",
			"data			TEXT",
			"image			TEXT default NULL",
			"stable_path		TEXT default NULL",
			"filter			TEXT",
			"created		DATETIME default (datetime('now'))", 
			"published		DATETIME default NULL",
			"updated		DATETIME default NULL", 
			"deleted		DATETIME default NULL", 
		];
		

		$this->gygDb->create('project', $columns);		

		tableMap::create('team', 'project');
		tableMap::create('user', 'project');
		tableTag::create('project');
		
		
		$this->gygDb->create('project_code_link_type',
		[
			'id 		INTEGER PRIMARY KEY',
			'url		TEXT NOT NULL',
			'image_path TEXT NOT NULL',
		]);
		
		// DEBUG
		if(count($this->gygDb->select('project_code_link_type', ['url' => 'https://github.com'])) === 0)
			$this->gygDb->insert('project_code_link_type', ['url' => 'https://github.com', 'image_path' => '/file/playhouse/pages/project/img/github.png']);
		
		if(count($this->gygDb->select('project_code_link_type', ['url' => 'https://github.coma'])) === 0)
			$this->gygDb->insert('project_code_link_type', ['url' => 'https://github.coma', 'image_path' => '/file/playhouse/pages/project/img/github.pnga']);
		// ENDDEBUG
		
		
		$this->gygDb->create('project_code_link',
		[
			'id 						INTEGER PRIMARY KEY',
			'url 						TEXT 	NOT NULL',
			'project_id 				INTEGER NOT NULL',
			'project_code_link_type_id	INTEGER NOT NULL',
			'FOREIGN KEY(project_id) REFERENCES project(id) ON DELETE CASCADE',
			'FOREIGN KEY(project_code_link_type_id) REFERENCES project_code_link_type(id) ON DELETE CASCADE',
		]);
	}



	/*
	 * Delete all rows from table.
	 */
	public function clear()
	{
		$userSession = new GygUserSession('user');
		if(!$userSession->hasClearance('admin'))
			httpStatus::send(403, "Permission denied. User does not have permission to access table function.");

		$this->gygDb->clear('project');
	}


	public function update($args)
	{
		$requiredArgs = 
		[
			'id',
			'key', 
			'title', 
			'codeNotice', 
			'description', 
			'image',
			'stablePath',
			'data', 
			'filter', 
			'updated'
		];
		
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]))
				httpStatus::send(405);

		tableMap::init($this->gygDb);
		$userSession = new GygUserSession('user');
		if(!$this->gygDb->rowExists(tableMap::get('user', 'project'), ['user_id' => $userSession->get()['id'], 'project_id' => $_POST['id']]))
			httpStatus::send(405, 'Project does not belong to user.');
				
		$oldKey = $_POST['key'];
		$key = parse::toUrlKey($_POST['title']);
		
		if(!$key)
			GygAjax::send(false, "Post title must contain numbers and/or letters.");
		
		// If the old key is the same as the new one, don't change key.
		if($key === $oldKey)
			$key = $oldKey;
		// Else create a new, unique key from the project's title. 
		else
			$key = parse::toUniqueTableValue('Project', 'key', $key, $this->gygDb);
		
		$projectId = $_POST['id'];
		$project = $this->gygDb->select('project', ['id' => $projectId]);
		
		if(count($project) === 0)
			httpStatus::send(405, 'Project does not exist');
			
		$project = $project[0];
		$updated = $_POST['stablePath'] == $project['stable_path'] ? $project['updated'] : date('Y-m-d H:i:s');
		$params = 
		[
			'key' 			=> $key,
			'title' 		=> htmlentities($_POST['title']),
			'code_notice'	=> htmlentities($_POST['codeNotice']),
			'description' 	=> htmlentities($_POST['description']),
			'data'			=> htmlentities($_POST['data']),
			'image'			=> htmlentities($_POST['image']),
			'stable_path'	=> htmlentities($_POST['stablePath']),
			'filter' 		=> $_POST['filter'],
			'updated' 		=> $updated,
		];
		
		if(isset($_POST['published']))
			$params['published'] = date('Y-m-d H:i:s');
		

		$this->gygDb->update('project', $params, ['id' => $projectId]);

		
		// Insert code links in foreign table.
		$codeLinkTypes = $this->gygDb->select('project_code_link_type');
		$codeLinks = $this->gygDb->select('project_code_link', ['project_id' => $projectId]);
		foreach($codeLinkTypes as $type)
		{
			if(isset($_POST["codeLink-{$type['id']}"]))
			{
				$url = $_POST["codeLink-{$type['id']}"];			
			
				// If url is empty string, request the code link's removal.
				if(!$url)
					$this->gygDb->delete('project_code_link', ['project_code_link_type_id' => $type['id']]);
				else
				{
					$typeAlreadyExists = false;
					foreach($codeLinks as $link)
						if($link['project_code_link_type_id'] === $type['id'])
						{
							$typeAlreadyExists = true;
							$this->gygDb->update('project_code_link', ['url' => $url], ['id' => $link['id']]);
						}	
					if($typeAlreadyExists === false)
					{
						
						$this->gygDb->insert('project_code_link', 
						[
							'url' => $url, 
							'project_id' => $projectId, 
							'project_code_link_type_id' => $type['id']
						]);
					}
				}
			}
		}

		GygAjax::send(true, "/playhouse/project/{$params['key']}");
	}

	public function insert($args)
	{
		$requiredArgs = 
		[
			'title', 
			'codeNotice', 
			'description', 
			'data', 
			'image',
			'stablePath',
			'filter', 
		];
		
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]))
				httpStatus::send(405);

		$userSession = new GygUserSession('user');
		if(!$userSession->hasClearance('admin'))
			httpStatus::send(403, 'User does not have permission.');

		$key = parse::toUrlKey($_POST['title']);
		
		if(!$key)
			GygAjax::send(false, "Project name must contain numbers and/or letters.");
		
		if($this->gygDb->rowExists('project', ['key' => $key]))
			GygAjax::send(false, "A project of that name already exists.");

		
		$params = 
		[
			'key' 			=> $key,
			'title' 		=> htmlentities($_POST['title']),
			'code_notice'	=> htmlentities($_POST['codeNotice']),
			'description' 	=> htmlentities($_POST['description']),
			'data'			=> htmlentities($_POST['data']),
			'image'			=> htmlentities($_POST['image']),
			'stable_path'	=> htmlentities($_POST['stablePath']),
			'filter' 		=> $_POST['filter'],
			'published' 	=> isset($_POST['published']) ? date('Y-m-d H:i:s') : null,
		];

		$this->gygDb->insert('project', $params);
		$projectId = $this->gygDb->lastInsertId();
		

		// Insert code links in foreign table.
		$codeLinkTypes = $this->gygDb->select('project_code_link_type');
		foreach($codeLinkTypes as $type)
		{
			if(isset($_POST["codeLink-{$type['id']}"]))
			{
				$url = $_POST["codeLink-{$type['id']}"];
		
				// Only insert it if the url contains anything.
				if($url)		
				{
					$this->gygDb->insert('project_code_link', 
					[
						'url' => $url, 
						'project_id' => $projectId, 
						'project_code_link_type_id' => $type['id']
					]);
				}
			}
		}
		
		// Insert ownership row.
		$binderParams = ['project_id' => $projectId];
		$isUser = $_POST['isUser'] === 'true';
		tableMap::init($this->gygDb);
		$binderTable = $isUser ? tableMap::get('user', 'project') : tableMap::get('team', 'project');
		if($isUser)
			$binderParams['user_id'] = $_POST['userId'];
		else
			$binderParams['team_id'] = $_POST['userId'];
		
		$this->gygDb->insert($binderTable, $binderParams);


		GygAjax::send(true, "/playhouse/project/{$params['key']}");
	}


	public function getCodeLinks($projectId = [])
	{
		$params = [];
		$where = null;
		if(count($projectId) > 0)
		{
			$where = 'WHERE project_code_link.project_id = ?';
			$params = [$projectId[0]];
		}
			
		$query =	"SELECT project_code_link.url, project_code_link_type.url as type_url, project_code_link_type.image_path 
					FROM project_code_link
					JOIN project_code_link_type
					ON project_code_link.project_code_link_type_id = project_code_link_type.id
					{$where}";

					
		$links = $this->gygDb->selectAndFetchAll($query, $params);
		return $links;
	}

	public function getPublished()
	{
		$res = $this->gygDb->selectAndFetchAll("SELECT * FROM project WHERE published IS NOT NULL ORDER BY created DESC");
		return $res;
	}

};