<?php

/*
 * Basic database functions for inserting and fetching data.
 */
class GygUserTable extends GygDatabaseTable
{	

	public function __construct($gygDb, $tableName)
	{
		parent::__construct($gygDb, $tableName);
		$this->init();
	}

	public function init()
	{
		// Return if already initialized.
		if($this->gygDb->tableExists('user'))
			return;

		// Define columns
		$properties = 
		[
			"id INTEGER PRIMARY KEY", 
			"name TEXT",
			"key TEXT KEY",
			"email TEXT",
			"password TEXT",
			"about TEXT",
			"grp TEXT",
			"created DATETIME default (datetime('now'))",
			"updated DATETIME default NULL"
		];

			
		// Make it.
		$this->gygDb->create('user', $properties);
		tableTag::create('user');
	}
	
	public function update($args)
	{
		if(!isset($_POST['about']) || !isset($_POST['id']))
			httpStatus::send(405);
			
			
		$this->gygDb->update('user', ['about' => parse::bbcode2html($_POST['about'])], ['id' => $_POST['id']]);
		ajax::send(true, 'Saved');
	}
	
	public function insert($args)
	{
		// Disable for now
		ajax::send(false, "User registration is currently disabled. If you want to register and take part of this website, please contact me (Mikael Hernvall) at  <img width=200px src='/file/playhouse/pages/user/mail.jpg'>.");
	
		$requiredArgs = 
		[
			'name', 
			'password'
		];
	
		foreach($requiredArgs as $arg)
			if(!isset($_POST[$arg]) && !isset($args[$arg]))
				httpStatus::send(405);
			else
				$args[$arg] = isset($args[$arg]) ? $args[$arg] : $_POST[$arg];
	
	
		$name = $args['name'];
		if(!$name)
			ajax::send(false, "Username field cannot be empty.");
		
		// Return false if user already exists in database.
		if($this->gygDb->rowExists('user', ['name' => $name]))
			ajax::send(false, "Username is occupied.");
	
		// Parse $name into an url friendly key.
		$key = parse::toUrlKey($name);
	
		// Return false if key already exists.
		if($this->gygDb->rowExists('user', ['key' => $key]))
			ajax::send(false, "Username is occupied.");
	
		if(!$args['password'])
			ajax::send(false, "Password field cannot be empty.");
			
		// Create the hashed password.
		$password = $this->createPassword($args['password']);

		
		// Set group.
		$group = isset($args['group']) ? $args['group'] : null;
		$group = $group ? $group : 'user';
		
		// Set user properties.
		$params =
		[
			'name' 		=> $name,
			'key' 			=> $key,
			'email' 		=> 'tmp',
			'password'		=> $password,
			'grp'			=> $group, // move this into a separate table
		];
		
		// Insert into database.
		$this->gygDb->insert('user', $params);
		if(!isset($arg['silent']) && $arg['silent'] !== true)
			ajax::send(true);
		else
			return true;
	}
	
	/**
	 * Hash a text string and return the result.
	 */
	public function createPassword($plain) 
	{
		return password_hash($plain, PASSWORD_DEFAULT);
	}
	
	/**
	* Check if password matches the password of a user
	* by hashing the password with the same algorithm as
	* the user has and comparing the hash strings.
	*
	* Return false if user does not exist.
	* Throw exception if user algorithm is not defined.
	* Return true if password matches.
	*/
	private function checkPassword($hash, $password) 
	{
		return password_verify($password, $hash);
	}
	
	/*
	 * Log user in.
	 *
	 * If given password matches the given user's,
	 * perform login and return true.
	 *
	 * Else return false.
	 */
	public function validateLogin($name, $password)
	{	
		// Get user data.
		$user = $this->gygDb->select($this->tableName, ['name' => $name]);
		
		// Return false if user does not exist.
		if(empty($user))
			return null;
			
		$user = $user[0];

		/*
		 * Check if password arg matches password
		 * property of user.
		 */
		if($this->checkPassword($user['password'], $password))
			return $user;
		else
			return null;
	}
};