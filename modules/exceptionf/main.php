<?php

// Enclose in C++-like namespace
class exceptionf
{
	static function go($message)
	{
		try
		{
			throw new Exception($message);
		}
		catch(Exception $e)
		{
			$trace = $e->getTrace();
			$origin = $trace[0];


			extract($origin);
			$message = $args[0];
			include(__DIR__ . '/template.php');

			

		}
		
		exit();
	}
}