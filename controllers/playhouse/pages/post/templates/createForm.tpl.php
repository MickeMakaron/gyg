<!--- Form for creating new programming posts --->

<form id="post-form">
	<input type="hidden" name="key" />
	<input type="hidden" name="tags" value="programming"/>
	<input type="hidden" name="userId" value="<?=$userSession->get()['id']?>"/>
	
	<div id="post" class="post">
		<h2><textarea id="post-form-title" type="text" name="title" value=""/></textarea></h2>
		
		<textarea id="post-form-data" name="data"></textarea>
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
		<button type="button" id="post-form-publish">Publish</button>
	</p>
	<p id="form-output"></p>
</form>