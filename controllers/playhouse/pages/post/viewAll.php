<?php
$args = $renderData['args'];


$posts = $postTable->getPublished();

if(count($posts) === 0)
{
	$renderData['content'] = "<p>There are no posts :(</p>";
	return;
}



ob_start();
// Loop through the posts and invoke the template file silently into buffer.
foreach($posts as $post)
{
	// Prepare post variables.
	include($gyg->getControllersPath() . '/playhouse/pages/post/getPost.php');
	$data = parse::bbcode2html($data);
	// Invoke template file.
	include($gyg->getControllersPath() . "/playhouse/pages/post/templates/post.tpl.php");
}
	
// Output buffer into gyg's content variable.
$renderData['content'] = ob_get_clean();


$renderData['title'] = 'Latest posts';
$renderData['description'] = 'Latest blog posts concerning programming.';






