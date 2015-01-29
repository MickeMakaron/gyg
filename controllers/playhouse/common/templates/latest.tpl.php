<div id="latestPosts" class="playhouseLink latest">
	<h2><a href="/playhouse/post">Latest posts</a></h2>
	<?php if($userSession->hasClearance('admin')): ?>
		<a class="latest-create" href="/playhouse/post/create">create</a>
	<?php endif; ?>
	<table>
		<?php for($i = 0; ($i < 3 && $i < count($posts)); $i++): $p = $posts[$i];?>
			<tr>
				<td><div class="latest-link"><p><a href="/playhouse/post/<?=$p['key']?>"><?=$p['title']?></a></p></div></td>
				<td><div class="latest-created"><p><?=$p['created']?></p></div></td>
			</tr>
		<?php endfor; ?>
	</table>
</div>


<div id="latestProjects" class="playhouseLink latest">
	<h2><a href="/playhouse/project">Latest projects</a></h2>
	<?php if($userSession->hasClearance('admin')): ?>
		<a class="latest-create" href="/playhouse/project/create">create</a>
	<?php endif; ?>
	
	<table>
		<?php for($i = 0; ($i < 3 && $i < count($projects)); $i++): $p = $projects[$i];?>
			<tr>
				<td><div class="latest-link"><p><a href="/playhouse/project/<?=$p['key']?>"><?=$p['title']?></a></p></div></td>
				<td><div class="latest-created"><p><?=$p['created']?></p></div></td>
			</tr>
		<?php endfor; ?>
	</table>
</div>