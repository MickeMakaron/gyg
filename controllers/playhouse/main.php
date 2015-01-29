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
 * ID of the requested controller. It is saved in $renderData['controller'].
 * You typically don't need to handle it at this level. To see it in use, see
 * gyg/route.php.
 *
 * PAGE
 * ID of the requested page. It is saved in $renderData['page'].
 * How your controller is going to handle this ID is entirely up to the author(s)
 * of this document's controller.
 * It is recommended that you use a whitelist for your pages. See the config file 
 * of the gyg controller for an example.
 *
 * ARGS
 * Arguments to be interpreted either by the controller or individual pages.
 * They are saved in $renderData['args']. If your controller contains a blog, an 
 * argument could be used like this:
 * 		Query		www.website.com?controller/blog/title
 *		RewriteRule	www.website.com/controller/blog/title
 *
 * where "blog" is the ID of your blog page and "title" is the ID of a 
 * blog post. See the "img" page on the file controller for an example.
 */

 
// Controller's config.
include(__DIR__ . "/config.php");
 

// Content common for all pages. I.e. headers, menus, standard stylesheet etc.
include_once(__DIR__ . "/common/commonContent.php");
 

/*
 * Page-specific content.
 */
$request = $gyg->getRequest();

$renderData['controller'] = $request['controller'];
$renderData['page'] = isset($request['args'][0]) ? $request['args'][0] : null;
$renderData['args'] = $request['argCount'] > 1 ? array_splice($request['args'], 1) : null;


// If null, go to home page.
if($renderData['page'] === null)
{
	include("pages/post/main.php");
	$renderData['title'] = null;
	$renderData['description'] = 'Personal programming blog and portfolio by Mikael Hernvall';
}
// Else if page is not whitelisted, go 404 and die.
else if(!$gyg->pageIsWhitelisted($renderData['page']))
	httpStatus::send('404');
// Else, go to page.
else
	include("pages/{$renderData['page']}/main.php");
	
extract($renderData);
include(__DIR__ . '/common/templates/main.tpl.php');
