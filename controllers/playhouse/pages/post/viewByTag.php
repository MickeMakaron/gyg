<?php
$tags = $args[1];

/*
$posts = $db->selectAndFetchAll(
'
	SELECT Post.*
	FROM Post
	JOIN PostTag
	ON Post.id = PostTag.postId
	WHERE PostTag.key=?
	ORDER BY Post.created DESC
', [$tags]);
*/


//$posts = tableMap::join('user_post_binder', 'Post', 'User', 1);
$posts = tableTag::getMasters('post', 'id', 'programming', 'created');

if(count($posts) === 0)
{
	$renderData['content'] = "<p>No posts found by those tags.</p>";
	return;
}

echo nl2br(print_r($posts, true));

$tags = $db->select('post_tag');

echo nl2br(print_r($tags, true));

ob_start();
// Loop through the posts and invoke the template file silently into buffer.
foreach($posts as $post)
{
	// Prepare post variables.
	include($gyg->getControllersPath() . 'playhouse/pages/post/getPost.php');
	
	// Invoke template file.
	include($gyg->getControllersPath() . "playhouse/pages/post/templates/post.tpl.php");
}
	
// Output buffer into gyg's content variable.
$renderData['content'] = ob_get_clean();






