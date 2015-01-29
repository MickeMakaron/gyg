<?php

/**
 * \brief Simple function wrapper for sending json data
 */
class GygAjax
{
	
	/**
	 * \brief Send json data
	 *
	 * Create a json object consisting of "success" and "output"
	 * properties and output it under appropiate HTTP header.
	 *
	 * Note that this function invokes exit(). Calling this function
	 * will thus exit the PHP script.
	 *
	 * \param success bool Indicate success on an ajax call.
	 * \param message any Arbitrary data.
	 */
	static public function send($success, $message = null)
	{
		header('Content-type: application/json');
		echo json_encode(['success' => $success, 'output' => $message]);
		exit();
	}
}