<?php

$key = $args[0];
$posts = $db->select('post', ['key' => $key]);
if(count($posts) === 0)
	httpStatus::send(404);
		
$post = $posts[0];

if(!$post['published'])
{
	if(!$userSession->isLoggedIn())
		httpStatus::send(404);
	
	if(!$db->rowExists(tableMap::get('post', 'user'), ['user_id' => $userSession->get()['id'], 'post_id' => $post['id']]))
		httpStatus::send(404);
}

include(__DIR__ . '/getPost.php');
$data = parse::bbcode2html($data);

foreach($comments as &$comment)
{
	$user = tableMap::join('user', 'comment', $comment['id']);
	$comment['userKey'] = count($user) > 0 ? "/playhouse/user/{$user[0]['key']}" : null;
}
array_push($renderData['scripts'], $gyg->path2url(__DIR__ . '/js/formComment.js'));
$postId = $post['id'];

ob_start();
include(__DIR__ . '/templates/post.tpl.php');
include(__DIR__ . '/templates/comments.tpl.php');

$renderData['content'] = ob_get_clean();

// Meta data
$renderData['title'] = $post['title'];
$renderData['keywords'] = array_merge($renderData['keywords'], [$post['title']]);