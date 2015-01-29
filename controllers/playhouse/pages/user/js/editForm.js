$(document).ready(function()
{
'use strict';


(function($) 
{	
	function success(data)
	{
		console.log(data);
		if(data.success)
			$('#form-output').html(data.output);
		else
			$('#form-output').html('Save failed.');
	}
	
	function save()
	{
		var data = $('#about-form').serialize();
		console.log(data);
	
		$.ajax(
		{
			type: 'post',
			url: '/ajax/playhouse/user?edit',
			data: data,
			dataType: 'json',
			success: success
		}); 
	}
	
	$('#save').click(save);
}) (jQuery);
});

