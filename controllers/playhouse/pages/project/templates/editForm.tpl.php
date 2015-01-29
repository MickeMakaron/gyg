<!--- Form for creating new programming projects --->

<form id="project-form">	
	<div id="project" class="projectPreview playhouseLink">
	
		<div id="project-header">
			<h2><textarea id="project-form-title" type="text" name="title"/><?=$title?></textarea></h2>
				
			<textarea id="project-form-description" name="description"><?=@$description?></textarea>
			
			
			
			<div id="project-codeNotice">
				<textarea id="project-form-codeNotice" name="codeNotice"><?=$code_notice?></textarea>
				
				<table id="project-form-codeSites">
					<?php foreach($codeLinks as $link): ?>
						<tr>
							<td><a href="<?=$link['url']?>"><img src="<?=$link['image_path']?>" height="32px"></a></td>
							<td><input type="text" name="codeLink-<?=$link['id']?>" value="<?=$link['codeUrl']?>"></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>

			
			<div id="project-contributors"><p>Contributors:</p>
				<p>
				<?php foreach($contributors as $contributor): ?>
					<a href="<?=$contributor['key']?>"><?=$contributor['name']?></a>
				<?php endforeach; ?>
				</p>
			</div>
		</div>
		
		<p>Link to stable version: <input id="project-forn-stable" type="text" name="stablePath" value="<?=$stable_path?>"/></p>
		<p>Project thumbnail: <input id="project-form-image" type="text" name="image" value="<?=$image?>" /></p>
		<textarea id="project-form-data" name="data"><?=@$data?></textarea>

		
		<div id="project-subtitle">
			<div id="project-created"><p>Created <?=$created?></p></div>

			<?php if($updated !== null): ?>
				<div id="project-updated"><p>Updated <?=$updated?></p></div>
			<?php endif; ?>
		</div>
	</div>
	
	<input type="hidden" name="id" value="<?=$id?>"/>
	<input type="hidden" name="key" value="<?=$key?>"/>
	<input type="hidden" name="updated" value="<?=date('Y-m-d H:i:s')?>"/>
	<input type="hidden" name="userId" vallue="<?=$userSession->get()['id']?>"/>
	<input type="hidden" name="filter" value="markdown"/>
	
	<p>
		<button type="button" id="project-form-save">Save</button>
		<?php if(!isset($published) || $published === null): ?>
			<button type="button" id="project-form-publish">Publish</button>
		<?php endif; ?>
	</p>
	<p id="form-output"></p>
</form>