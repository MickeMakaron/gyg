<!---Template for user profile on user page. --->

<h1>Admin panel</h1>
<p>Drop tables</p>
<table>
	<?php foreach($tables as $table): ?>
		<tr>
			<td><button type="button" value="<?=$table['name']?>"><?=$table['name']?></button></td>
			<td><div id="<?=$table['name']?>-output"></div></td>
		</tr>
	<?php endforeach; ?>
</table>
<div id="form-output"></div>