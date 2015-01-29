<?php

/* Main controller document.
 * 
 * This main document defines how this controller interprets incoming
 * arguments parsed by gyg/route.php. All arguments are guaranteed to be set
 * by gyg/route.php. If an argument is not given by the user, it is set as null.
 *
 * Argument structure:
 * 		Query: 		www.website.com?controller/page/arg1/arg2/arg3...
 *		RewriteRule	www.website.com/controller/page/arg1/arg2/arg3...
 *
 * Arguments are set as follows:
 * CONTROLLER 
 * ID of the requested controller. It is saved in $gyg['controller'].
 * You typically don't need to handle it at this level. To see it in use, see
 * gyg/route.php.
 *
 * PAGE
 * ID of the requested page. It is saved in $gyg['page'].
 * How your controller is going to handle this ID is entirely up to the author(s)
 * of this document's controller.
 * It is recommended that you use a whitelist for your pages. See the config file 
 * of the gyg controller for an example.
 *
 * ARGS
 * Arguments to be interpreted either by the controller or individual pages.
 * They are saved in $gyg['args']. If your controller contains a blog, an 
 * argument could be used like this:
 * 		Query		www.website.com?controller/blog/title
 *		RewriteRule	www.website.com/controller/blog/title
 *
 * where "blog" is the ID of your blog page and "title" is the ID of a 
 * blog post. See the "img" page on the file controller for an example.
 */

/*
 * The database page consists purely of ajax response
 * documents. Users are not supposed to access the database page
 * in the browser. 
 *
 * URI request is interpeted as follows:
 * 		table/function/param1/param2/...
 * At least two arguments are required: the table's name and the function's
 * name. 
 *
 * No authorization checks are done at this stage. This means anyone
 * will be able to call any enabled table function by accessing this page
 * manually. Authorization checks are thus ideally set up in the table's 
 * functions file.
 */
 
// Interpret the page argument as table name.
$request = $gyg->getRequest();
$argCount = $request['argCount'];

// We need at least two arguments: table name and function name.
if($argCount < 2)
{
	echo('Insufficient arguments. Need table name and function name.');
	die();
}

$args = $request['args'];

// Set first argument as table name.
$table = $args[0];

// Set second argument as table function name
$function = $args[1];

// Set remaining arguments as function arguments.
$params = array_splice($args, 2);
	
/*
 * Call the requested table function and let it do its work.
 * We don't need to bother checking whether the table and
 * function is whitelisted. This is done by the 
 * $db->callTableFunction function.
 */
$db = new GygDatabase($dsn);

$tableObj = new GygDatabaseTable($db, $table);

$tableObj->callTableFunctionByAjax($function, $params);
// Exit script to prevent any rendering.
exit();