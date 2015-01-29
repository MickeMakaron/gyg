<?php


// Enclose in parse namespace
Class parse
{	
	/*
	 * Parse a string into a URL-friendly string.
	 *
	 * Resulting string will only contain (1) lower-case 
	 * letters, (2) numbers and (3) dashes.
	 *
	 * Dashes substitute characters between numbers and letters.
	 * Examples:
	 * 'SuperMan!' 				=> 'superman'
	 * 'Super Man!' 			=> 'super-man'
	 * 'Super! Man!' 			=> 'super-man'
	 * 'Super!%#%"Man!!!#   ""2' => 'super-man-2'
	 */
	static function toUrlKey($key)
	{
		// Remove all non-letters and non-numbers from key and replace them with dashes.
		$key = preg_replace('/[^a-zA-z0-9]/', '-', $key);
		
		// Trim all trailing dashes.
		$key = trim($key, " \t-");
		
		// Remove clumps of dashes.
		$key = preg_replace('/-{1,}/', '-', $key);
		
		// Finally set all characters to lower case.
		$key = strtolower($key);
		
		return $key;
	}

	/*
	 * Parse tags into URL-friendly strings.
	 */
	static function tags($tags)
	{
		$tags = explode(' ', $tags);
		
		foreach($tags as $tag)
		{
			$tag = self::toUrlKey($tag);
			if($tag === '')
				unset($tag);
		}
		
		$tags = implode(' ', $tags);
		return $tags;
	}

	/*
	 * Make a string key unique by checking if the key exists in the given table.
	 * While key is not unique in that table, alter it until it is.
	 * The key is altered by simply appending an integer to the key and incrementing it
	 * until the key is unique. 
	 *
	 * For example, if "key" already exists, makeKeyUnique will return
	 * "key1". If also "key1" already exists, "key2" will be returned, and so on.
	 *
	 * @params:
	 * tableId, string,	Id of table to look in.
	 * col,		string, Id of column in table to compare key to.
	 * key, 	string, Key to make unique.
	 */
	static function toUniqueTableValue($tableId, $col, $key, $db)
	{
		/*
		 *If key is not unique, append integer until it is
		 * and return the new key.
		 */
		if(count($db->select($tableId, [$col => $key])) > 0)
		{
			$counter = 1;
			$uniqueKey = $key . $counter;
			while(count($db->select($tableId, [$col => $key . $counter])) > 0)
			{
				$counter++;
				$uniqueKey = $key . $counter;
			}
			
			return $uniqueKey;
		}
		
		// Else simply return the key as it is.
		return $key;
	}


	/*
	 * Convert bbcode elements in string to html elements.
	 */
	 static function bbcode2html($str)
	 {
		$bbcode =
		[
			'url' 		=> "/\[url=(.*?)\](.*?)\[\/url\]/",
			'bold' 		=> "/\[b\](.*?)\[\/b\]/",
			'italic' 	=> "/\[i\](.*?)\[\/i\]/",
			'img'		=> "/\[img=(.*?)\]/",
			'h1'		=> "/\[h1\](.*?)\[\/h1\]/",
			'h2'		=> "/\[h2\](.*?)\[\/h2\]/",
		];
		
		$html =
		[
			'url' 		=> '<a href="$1">$2</a>',
			'bold' 		=> "<b>$1</b>",
			'italic' 	=> "<i>$1</i>",
			'img'		=> '<div class="image-wrapper" ><img src="$1"/></div>',
			'h1'		=> '<h1>$1</h1>',
			'h2'		=> '<h2>$1</h2>',
		];
	 
		$str = preg_replace($bbcode, $html, $str);
		$str = nl2br($str);
		
		return $str;
	 }
};