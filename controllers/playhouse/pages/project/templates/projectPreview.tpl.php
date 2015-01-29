<div class="projectPreview playhouseLink">
	<img id="project-stable-indicator" src="/file/playhouse/img/<?php if($stable_path) echo "projectHasStable.png"; else echo "projectHasNotStable.png"?>" alt="project stable indicator">


	<?=@$adminPanel?>

	<table id="project-preview">	
		<tr>
			<td>
				<div id="project-title"><h2>
					<a href="/playhouse/project/<?=$key?>">
					<?=$title?>
					</a>
				</h2></div>
				
				
				<div id="project-subtitle">
					<?php if ($updated !== null): ?>
						<div id="project-updated"><p>Updated <?=$updated?></p></div>
					<?php endif; ?>
				</div>
				<div id="project-description"><?=$description?></div>
			</td>
			<td>
				<?php if($image): ?>
					<div id="project-thumbnail"><img src="<?=$image?>" alt="project thumbnail <?=$title?>" width="64px"></div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>