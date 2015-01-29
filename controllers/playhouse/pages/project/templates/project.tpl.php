<div id="project" class="projectPreview playhouseLink">
	<?php if($image): ?>
		<div id="project-image"><img src="<?=$image?>" alt="project thumbnail <?=$title?>" width="32px"></div>
	<?php endif; ?>
	<div id="project-header">
		<?=@$adminPanel?>
		
		<div id="project-title"><h2>
			<a href="/playhouse/project/<?=$key?>">
			<?=$title?>
			</a>
		</h2></div>
		
		<div id="project-description"><?=$description?></div>

		<?php if($code_notice || count($codeLinks) > 0): ?>

			<div id="project-codeNotice">
				<p><?=$code_notice?></p>
				<div id="project-codeSites">
					<?php foreach($codeLinks as $link): ?>
						<a href="<?=$link['url']?>"><img src="<?=$link['image_path']?>" height="32px"></a>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<div id="project-contributors"><p>Contributors:</p>
			<p>
			<?php foreach($contributors as $contributor): ?>
				<a href="<?=$contributor['key']?>"><?=$contributor['name']?></a>
			<?php endforeach; ?>
			</p>
		</div>
	</div>
	
	<?php if($stable_path): ?>
		<div id="project-stable"><a href="<?=$stable_path?>" alt="stable release <?=$title?>">Stable (<?php echo ($updated ? $updated : $created); ?>) </a> <img src="/file/playhouse/img/projectHasStable.png"></div>
	<?php else: ?>
		<div id="project-stable">Unstable <img src="/file/playhouse/img/projectHasNotStable.png"></div>
	<?php endif;?>
	
	<div id="project-data"><?=$data?></div>
	
	<div id="project-subtitle">
		<div id="project-created"><p>Created <?=$created?></p></div>
	</div>
</div>