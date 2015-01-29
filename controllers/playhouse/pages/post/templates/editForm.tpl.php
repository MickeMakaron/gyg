<!--- Form for creating new programming posts --->

<form id="post-form">
	<input type="hidden" name="id" value="<?=$id?>"/>
	<input type="hidden" name="key" value="<?=$key?>"/>
	<input type="hidden" name="tags" value="programming"/>
	<input type="hidden" name="userId" value="<?=$userSession->get()['id']?>"/>
	<input type="hidden" name="updated" value="<?php echo date('Y-m-d H:i:s');?>"/>
	
	<div id="post" class="post">
		<h2><textarea id="post-form-title" type="text" name="title"/><?=$title?></textarea></h2>

		<div id="post-subtitle">
			<?php if($project !== null): ?>
				<div id="post-project"><p>A part of the <a href="/playhouse/project/<?=$projectKey?>"><?=$project?></a> project</p></div>
			<?php endif; ?>
		
			<div id="post-created"><p>by 
				<?php foreach($authors as $author): ?>
					<a href="<?=$author['key']?>"><?=$author['name']?></a>,
				<?php endforeach; ?>	
				on <?=$created?></p></div>
			<?php if($updated !== null): ?>
				<div id="post-updated"><p>Updated <?=$updated?></p></div>
			<?php endif; ?>
		</div>
		<textarea id="post-form-data" name="data"><?=@$data?></textarea>
	</div>
	
	<p><input type="hidden" name="filter" value="markdown"/></p>
	
	<p>
		<select name="project">
			<option selected value="null">Link to project</option>
			<?php foreach($projects as $p): ?>
				<option value="<?=$p['id']?>"><?=$p['title']?></option>
			<?php endforeach; ?>
		</select>
	</p>
	
	<p>
		<button type="button" id="post-form-save">Save</button>
		<?php if(!isset($published) || $published === null): ?>
			<button type="button" id="post-form-publish">Publish</button>
		<?php endif; ?>
	</p>
	<p id="form-output"></p>
</form>