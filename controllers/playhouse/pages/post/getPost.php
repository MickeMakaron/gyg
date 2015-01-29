<?php	
extract($post);


$authors = tableMap::join('user', 'post', $id);
foreach($authors as &$author)
	$author['key'] = '/playhouse/user/' . $author['key'];

$teams = tableMap::join('team', 'post', $id);
foreach($teams as &$team)
	$team['key'] = '/playhouse/team/' . $team['key'];
	
$authors = array_merge($authors, $teams);

if(count($authors) === 0)
	$authors = [array('key' => '/playhouse/team/spookyskeletons', 'name' => 'Spooky Skeletons')];

// Only use the date. Remove the time.
$created = substr($created, 0, 10);
if($updated !== null)
	$updated = substr($updated, 0, 10);
	
$res = tableMap::join('project', 'post', $id);

if(count($res) > 0)
{
	$res = $res[0];
	$project = $res['title'];
	$projectKey = $res['key'];
	$projectImage = $res['image'];
}
else
{
	$project = $projectKey = $projectImage = null;
}

$comments = $db->select('comment', ['post_id' => $id]);

if($userSession->hasClearance('admin'))
{	
	ob_start();
	include(__DIR__ . '/templates/admin.tpl.php');
	
	$adminPanel = ob_get_clean();
}