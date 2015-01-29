<?php
extract($project);

$users = tableMap::join('user', 'project', $id);
foreach($users as &$u)
	$u['key'] = '/playhouse/user/' . $u['key'];

$teams = tableMap::join('team', 'project', $id);
foreach($teams as &$team)
	$team['key'] = '/playhouse/team/' . $team['key'];
	
$contributors = array_merge($users, $teams);

if(count($contributors) === 0)
	$contributors = [array('key' => '/playhouse/user/spookyskeletons', 'name' => 'Spooky Skeletons')];

	
// Only use the date. Remove the time.
$created = substr($created, 0, 10);
if($updated !== null)
	$updated = substr($updated, 0, 10);

	
$codeLinks = $projectTable->getCodeLinks([$id]);

if($userSession->hasClearance('admin'))
{	
	ob_start();
	include(__DIR__ . '/templates/admin.tpl.php');
	
	$adminPanel = ob_get_clean();
}