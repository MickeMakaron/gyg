<div id="post" class="post">
	<?php if($projectImage): ?>
		<div id="post-thumbnail"><div>
			<a href="/playhouse/project/<?=$projectKey?>">
				<img src="<?=$projectImage?>" alt="project thumbnail <?=$projectKey?>" width="32px">
			</a>
		</div></div>
	<?php endif; ?>
	<div id="post-title"><h2>
		<a href="/playhouse/post/<?=$key?>">
		<?=$title?>
		</a>
	</h2></div>
	<div id="post-subtitle">
		<?=@$adminPanel?>
	
		<?php if($project): ?>
			<div id="post-project"><p>A part of the <a href="/playhouse/project/<?=$projectKey?>"><?=$project?></a> project</p></div>
		<?php endif; ?>

		<div id="post-created">
			<p>
				by
				<?php foreach($authors as $author): ?>
					<a href="<?=$author['key']?>"><?=$author['name']?></a>
				<?php endforeach; ?>
				on <?=$created?>
			</p>
		
		</div>
		
		<?php if ($updated !== null): ?>
			<div id="post-updated"><p>Updated <?=$updated?></p></div>
		<?php endif; ?>
	</div>
	<div id="post-data"><?=$data?></div>
</div>