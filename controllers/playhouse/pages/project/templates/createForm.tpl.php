<!--- Form for creating new programming projects --->

<form id="project-form">
	<div id="project" class="projectPreview playhouseLink">
		<div id="project-header">
			<h2><textarea id="project-form-title" type="text" name="title"/>Project title</textarea></h2>
			
			<textarea id="project-form-description" name="description">Short abstract...</textarea>

			<div id="project-codeNotice">
				<textarea id="project-form-codeNotice" name="codeNotice">Code availability and licensing.</textarea>
				<table id="project-form-codeSites">
					<?php foreach($codeLinks as $link): ?>
						<tr id="project-form-codeSite">
							<td><a href="<?=$link['url']?>"><img src="<?=$link['image_path']?>" height="32px"></a></td>
							<td><input id="project-form-codeSiteId-<?=$link['id']?>" type="text" name="codeLink-<?=$link['id']?>" /></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			
			<textarea id="project-form-contributors" name="contributors">Contributing users separated by spaces</textarea>
		</div>
		
		
		<p>Link to stable version: <input id="project-forn-stable" type="text" name="stablePath" /></p>
		<p>Project thumbnail: <input id="project-form-image" type="text" name="image" /></p>
		<textarea id="project-form-data" name="data">Extensive description...</textarea>
	</div>
	
	<input type="hidden" name="userId" value="<?=$userSession->get()['id']?>"/>
	<input type="hidden" name="isUser" value="true"/>
	<input type="hidden" name="filter" value="markdown"/>
	<p>
		<button type="button" id="project-form-save">Save</button>
		<button type="button" id="project-form-publish">Publish</button>
	</p>
	<p id="form-output"></p>
</form>