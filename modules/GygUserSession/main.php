<?php
class GygUserSession
{	
	private $sessionName;

	/**
	 * Create session.
	 */
	public function __construct($sessionName)
	{
		$this->sessionName = $sessionName;
		
		// Start session if there isn't one.
		if (session_status() == PHP_SESSION_NONE)
			session_start();
	}
	
	/**
	 * \brief Get data of logged in user.
	 *
	 * \return array Data of logged in user. If user is not logged in null is returned.
	 */
	public function get()
	{
		if(!$this->isLoggedIn())
			return null;
		else
			return $_SESSION[$this->sessionName];
	}
	
	/*
	 * Check if a user is logged in by checking the value of $this->$user.
	 *
	 * If null, return false. Else return true.
	 */
	public function isLoggedIn()
	{
		return isset($_SESSION[$this->sessionName]);
	}
	
	/*
	 * Check if the group property of the currently logged in user
	 * matches the $group argument. 
	 *
	 * Return false if no user is logged in.
	 * Return true if logged in user's group matches $group argument.
	 * Else return false.
	 */
	public function hasClearance($group)
	{
		if(!$this->isLoggedIn())
			return false;
	
		if($_SESSION[$this->sessionName]['grp'] === $group)
			return true;
		else
			return false;
	}	

	/*
	 * Log out current user.
	 */
	public function logout()
	{
		if(!$this->isLoggedIn())
			return true;
			
		unset($_SESSION[$this->sessionName]);
		return true;
	}
	
	public function login($userData)
	{
		$requiredData = ['id', 'name', 'key', 'email', 'about', 'grp', 'created', 'updated'];
		
		foreach($requiredData as $data)
			if(!isset($userData[$data]) && !array_key_exists($data, $userData))
				throw new Exception("User data missing at login. No {$data} given.");
	
		if(isset($userData['password']))
			unset($userData['password']);
	
		$_SESSION[$this->sessionName] = $userData;
	}
};