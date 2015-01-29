<!---Template for user profile on user page. --->

<div id="user-menu" class="playhouseLink">
		<h2><?php if($userSession->isLoggedIn()) echo 'User profile'; else echo "About {$user['name']}"; ?></h2>
			<div id="user-about">
				<?=$user['about']?>
			</div>
		<?php if($userSession->isLoggedIn() && $userSession->get()['id'] === $user['id']): ?>
			<p><a href="/playhouse/user/edit">Edit about</a></p>
		<?php endif; ?>
		<hr>
		<?php if(count($posts) > 0): ?>
			<h2>Posts</h2>
			<table id="posts">
				<?php foreach($posts as $post): ?>
					<tr>
						<td><a href="/playhouse/post/<?=$post['key']?>"><?=$post['title']?></td>
						<td><p><?=$post['created']?></p></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
		<hr>
		<?php if(count($projects) > 0): ?>
			<h2>Projects</h2>
			<table id="projects">
				<?php foreach($projects as $project): ?>
					<tr>
						<td><a href="/playhouse/project/<?=$project['key']?>"><?=$project['title']?></td>
						<td><p><?php echo($project['updated'] ? $project['updated'] : $project['created']); ?></p></td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
</div>